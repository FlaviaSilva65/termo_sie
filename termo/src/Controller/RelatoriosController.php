<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Relatorio;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Authorization\Exception\ForbiddenException;
use Cake\I18n\Date;
use DateTime;

/**
 * Relatorios Controller
 *
 * @property \App\Model\Table\RelatoriosTable $Relatorios
 */
class RelatoriosController extends AppController
{
    // public function initialize(): void
    // {
    //     parent::initialize();
    //     // $this->loadComponent('CrachaDigital');
    //     $this->Authentication->allowUnauthenticated(['manterPerguntas']);
    // }

    public function manterPerguntas($dimensao, $escola_id, $id = null)
    {
        $this->Authorization->skipAuthorization();

        // Mapeia perfis que só navegam/assinam (não editam nem salvam rascunho)
        $camposAssinaturaPorPerfil = [
            1 => 'id_ass_dir',   // Diretor
            5 => 'id_ass_assis', // Assistente
            4 => 'id_ass_sub',   // Subsecretaria
            8 => 'id_ass_sub',   // Subsecretaria-adjunto
        ];

        $modoSomenteLeitura = $this->identity?->isAdministrador ?: $this->identity?->isGestor;

        $queryPerguntas = $this->fetchTable('Perguntas');
        $queryOcorrencias = $this->fetchTable('Ocorrencias');

        $Respostas = $this->fetchTable('Respostas');
        $OcorrenciaRelatorios = $this->fetchTable('OcorrenciaRelatorios');
        $Providencia = $this->fetchTable('Providencias');

        // ------ MODO: pendências? ------
        $somentePendencias = (bool)$this->request->getQuery('pendencias');

        $relatorio = $this->Relatorios->newEmptyEntity();

        $forcarNovo = (bool)$this->request->getQuery('novo');

        if ($id) {
            $relatorio = $this->Relatorios->get($id);

            // dd($relatorio->gestores);
        } else {

            $relatorioExistente = $this->Relatorios->find()
                ->where([
                    'person_id' => $this->identity?->person['id'],
                    'place_id' => $escola_id,
                    'YEAR(created)' => date('Y'),
                    'MONTH(created)' => date('m'),
                    'ic_rascunho' => 1, // ainda em rascunho
                ])
                ->orderByDesc('id')
                ->first();


            if ($relatorioExistente) {
                $this->Authorization->authorize($relatorioExistente, 'manterPerguntas');
                return $this->redirect(['action' => 'manterPerguntas', $dimensao, $escola_id, $relatorioExistente->id]);
            }

            $relatorio_ano = $this->Relatorios->find()->where(['place_id' => $escola_id, 'YEAR(created)' => date('Y')])->all();
            $ultimo_relatorio = $relatorio_ano->last();

            $termo_id = $ultimo_relatorio ? $ultimo_relatorio->termo_id + 1 : 1;

            $relatorio = $this->Relatorios->newEntity([
                'termo_id' => $termo_id,
                'person_id' => $this->identity?->person['id'],
                'place_id' => $escola_id,
                'data' => date('Y-m-d'),
                'ic_rascunho' => 1, // 1 = rascunho, 0 = finalizado
            ]);

            // dd($relatorio);

            $this->Relatorios->save($relatorio);
            // dd($relatorio);
            return $this->redirect(['action' => 'manterPerguntas', $dimensao, $escola_id, $relatorio->id]);
        }

        $temAssinaturaDir = !empty($relatorio->id_ass_dir);
        $temAssinaturaAssis = !empty($relatorio->id_ass_assis);
        $temAssinaturaSup = !empty($relatorio->id_ass_super);
        $temAssinaturaSub = !empty($relatorio->id_ass_sub);

        $emRascunho  = $relatorio->ic_rascunho == 1;


        /**
         * status:
         *  assinado           - usuário já assinou
         *  pode_assinar       - usuário pode assinar agora
         *  rascunho           - supervisor ainda está editando
         *  aguardando_gestores- subsecretário aguardando diretor/assistente
         *  nenhum             - perfil sem ação de assinatura
         */
        $status = 'nenhum';

        if ($this->identity?->isSupervisor) {
            $status = $emRascunho ? 'pode_assinar' : 'assinado';
        } elseif ($emRascunho && ($this->identity?->isAdministrador ?: $this->identity?->isGestor)) {
            $status = 'rascunho';
        } elseif ($this->identity?->isGestor) {
            $status = $temAssinaturaDir ? 'assinado' : 'pode_assinar'; // ($relatorio->pendencia == 'sim' ? 'aguardando_pendencia' : '');
        } elseif ($this->identity?->isGestor) {
            $status = $temAssinaturaAssis ? 'assinado' : 'pode_assinar';  // ($relatorio->pendencia == 'sim' ? 'aguardando_pendencia' : 'pode_assinar');
        } elseif ($this->identity?->isAdministrador) {
            if ($temAssinaturaSub) {
                $status = 'assinado';
            } else {
                // $status = $gestoresOk ? 'pode_assinar' : 'aguardando_gestores';
            }
        }

        // Se houver assinatura de algum gestor o Supervisor não pode alterar.

        if ($this->identity?->isSupervisor) {
            $modoSomenteLeitura =
                !empty($relatorio->id_ass_dir)
                || !empty($relatorio->id_ass_assis);
        }

        $assinaturaUsuario = [
            'status'      => $status,
            'assinatura'  => $status === 'assinado',
            'podeAssinar' => $status === 'pode_assinar',
            // 'faltam'      => $faltam,
        ];

        $this->set(compact('assinaturaUsuario'));

        $this->Authorization->authorize($relatorio, 'manterPerguntas');

        if ($relatorio->id_ass_super != null) {
            $desabilitaSalvarRasc = 'disabled';
        } else {
            $desabilitaSalvarRasc = '';
        }

        //Monta o set da seção do termo e garante o nº de dimensões de
        // if (!$somentePendencias) {
        $dimensao = $this->secoesTermo($dimensao, $queryPerguntas, $relatorio->id);
        // }

        if (
            $this->identity?->isGestor &&
            $dimensao == 7
        ) {
            return $this->redirect([
                'action' => 'manterPerguntas',
                6,
                $escola_id,
                $id
            ]);
        }


        // ----- Se estiver em modo pendências, calcula quais dimensões ainda têm pendência -----
        $dimensoesComPendencia = [];

        if ($somentePendencias) {
            $perguntaIdsPendentesGeral = $Providencia->find()
                ->select(['pergunta_id'])
                ->where(['relatorio_id' => $id, 'status' => 0])
                ->all()
                ->extract('pergunta_id')
                ->toArray();

            if (!empty($perguntaIdsPendentesGeral)) {
                $dimensoesComPendencia = $queryPerguntas->find()
                    ->select(['dimensao'])
                    ->where(['id IN' => $perguntaIdsPendentesGeral])
                    ->distinct(['dimensao'])
                    ->orderByAsc('dimensao')
                    ->all()
                    ->extract('dimensao')
                    ->toArray();
            }

            // Se a dimensão atual não tem mais pendência (ex: acabou de resolver a última),
            // pula pra próxima dimensão da lista, ou volta ao painel se não sobrar nenhuma.
            if (!$this->request->is(['post', 'put'])) {
                if (!empty($dimensoesComPendencia) && !in_array($dimensao, $dimensoesComPendencia)) {
                    $proxima = null;
                    foreach ($dimensoesComPendencia as $d) {
                        if ($d > $dimensao) {
                            $proxima = $d;
                            break;
                        }
                    }
                    if ($proxima) {
                        return $this->redirect(['action' => 'manterPerguntas', $proxima, $escola_id, $id, '?' => ['pendencias' => 1]]);
                    }
                    $this->Flash->success('Todas as pendências deste relatório foram resolvidas.');
                    return $this->redirect(['action' => 'dash-supervisor']);
                }
            }
        }

        if ($this->request->is(['post', 'put'])) {
            if ($modoSomenteLeitura) {
                $this->response = $this->response
                    // ->withStatus(403)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['success' => false, 'message' => 'Sem permissão para editar.']));
                $this->autoRender = false;
                return $this->response;
            }
            $data = $this->request->getData();

            foreach ($data['respostas'] ?? [] as $perguntaId => $resposta) {
                $respostaAtual = $Respostas->find()
                    ->select(['id', 'status'])
                    ->where([
                        'relatorio_id' => $id,
                        'pergunta_id' => $perguntaId
                    ])
                    ->first();

                $statusInformado = $respostaAtual
                    ? (int)$respostaAtual->status
                    : 1;

                // $statusInformado = isset($resposta['status']) ? (int)$resposta['status'] : 1;
                $observacao = trim((string)($resposta['observacao'] ?? ''));

                if ($statusInformado === 0 && $observacao === '') {
                    $mensagem = 'Informe a observação das perguntas com acompanhamento requerido antes de salvar.';

                    $ehAjax = $this->request->is('ajax')
                        || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

                    if ($ehAjax) {
                        $this->autoRender = false;
                        return $this->response
                            ->withType('application/json')
                            ->withStringBody(json_encode([
                                'success'     => false,
                                'message'     => $mensagem,
                                'campo'       => 'observacao',
                                'pergunta_id' => (int)$perguntaId,
                            ]));
                    }

                    $this->Flash->error($mensagem);
                    return $this->redirect($this->referer());
                }
            }

            $limiteData    = $relatorio->created ? $relatorio->created->format('Y-m-d') : date('Y-m-d');
            $dataInformada = $data['relatorio']['data'] ?? null;

            if (!empty($dataInformada) && $dataInformada > $limiteData) {
                $mensagem = 'A data da visita não pode ser posterior à data de criação do relatório.';

                if ($this->request->is('ajax')) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode(['success' => false, 'message' => $mensagem]));
                }

                $this->Flash->error($mensagem);
                return $this->redirect($this->referer());
            }

            \Cake\Log\Log::debug('DADOS CoMPLETOS: ' . json_encode($data));

            if (!empty($data['relatorio'])) {
                $relatorio = $this->Relatorios->patchEntity($relatorio, $data['relatorio']);
                if (!$this->Relatorios->save($relatorio)) {
                    \Cake\Log\Log::debug('Erro ao salvar relatorio: ' . json_encode($relatorio->getErrors()));
                }
            }

            // Se veio a data da visita, registra também em Respostas
            // para contar como pergunta respondida na dimensão.
            if (!empty($data['relatorio']['data'])) {
                $perguntaData = $queryPerguntas->find()
                    ->select(['id'])
                    ->where(['dimensao' => $dimensao, 'tipo' => 'data'])
                    ->first();

                if ($perguntaData) {
                    $existenteData = $Respostas->find()
                        ->where(['relatorio_id' => $id, 'pergunta_id' => $perguntaData->id])
                        ->first();

                    $entityData = $existenteData ?: $Respostas->newEntity([
                        'relatorio_id' => $id,
                        'pergunta_id' => $perguntaData->id,
                    ]);

                    $novaData = (string)$data['relatorio']['data'];

                    if (
                        $entityData->isNew() ||
                        (string)($entityData->resposta ?? '') !== $novaData
                    ) {
                        $entityData->resposta = $novaData;
                        $entityData->status = 1; // sempre considerada "sem pendência"

                        $Respostas->saveOrFail($entityData);
                    }

                    // $entityData->resposta = $data['relatorio']['data'];
                    // $entityData->status = 1; // sempre considerada "sem pendência"
                    // $Respostas->save($entityData);
                }
            } elseif (empty($relatorio->data)) {
                // Se ainda não existe data no relatório, salva a data atual
                $relatorio = $this->Relatorios->get($id);

                if (empty($relatorio->data)) {
                    $relatorio->data = new \Cake\I18n\Date();
                    $this->Relatorios->saveOrFail($relatorio);
                }
            }


            // Salva o usuário selecionado na pergunta 35 (id_usuario)
            if ($this->request->getData('responsavel_id')) {
                $relatorio = $this->Relatorios->patchEntity($relatorio, [
                    'responsavel_id' => $this->request->getData('responsavel_id'),
                ]);

                if (!$this->Relatorios->save($relatorio)) {
                    \Cake\Log\Log::debug('Erro ao salvar responsavel_id: ' . json_encode($relatorio->getErrors()));
                }
            }

            // Ids de perguntas válidas para este conjunto (evita erro de FK com ids "fantasmas")
            $perguntaIdsValidasPost = $queryPerguntas->find()
                ->select(['id'])
                ->all()
                ->extract('id')
                ->toArray();

            $datasRespostas = [];

            foreach ($data['respostas'] ?? [] as $perguntaId => $resposta) {

                $existente = $Respostas->find()
                    ->where([
                        'relatorio_id' => $id,
                        'pergunta_id' => $perguntaId
                    ])
                    ->first();

                $entity = $existente ?: $Respostas->newEntity([
                    'relatorio_id' => $id,
                    'pergunta_id' => $perguntaId,
                ]);

                // -------------------------------------
                // VERIFICA SE HOUVE ALTERAÇÃO
                // -------------------------------------

                $alterou = false;

                // Verifica alteração na resposta
                if (array_key_exists('resposta', $resposta)) {

                    $novaResposta = $resposta['resposta'];

                    if ((string)($entity->resposta ?? '') !== (string)($novaResposta ?? '')) {

                        $entity->resposta = $novaResposta;
                        $alterou = true;
                    }
                }

                // Verifica alteração na observação
                if (array_key_exists('observacao', $resposta)) {

                    $novaObservacao = $resposta['observacao'];

                    if ((string)($entity->observacao ?? '') !== (string)($novaObservacao ?? '')) {

                        $entity->observacao = $novaObservacao;
                        $alterou = true;
                    }
                }

                // Status somente para respostas novas
                if ($entity->isNew()) {
                    $entity->status = 1;
                }

                // -------------------------------------
                // SALVA SOMENTE SE NECESSÁRIO
                // -------------------------------------

                $salvouResposta = false;

                if ($entity->isNew()) {

                    // Verifica se existe resposta ou observação
                    $temResposta = trim((string)($entity->resposta ?? '')) !== '';
                    $temObservacao = trim((string)($entity->observacao ?? '')) !== '';

                    // Perguntas 16 e 18: verifica ocorrências marcadas
                    $temOcorrencias = in_array((int)$perguntaId, [16, 18], true)
                        && !empty(array_filter(
                            $resposta['ocorrencias'] ?? [],
                            fn($valor) => (string)$valor === '1'
                        ));

                    if ($temResposta || $temObservacao || $temOcorrencias) {

                        $Respostas->saveOrFail($entity);
                        $salvouResposta = true;
                    }
                } elseif ($alterou) {

                    // Atualiza somente respostas modificadas
                    $Respostas->saveOrFail($entity);
                    $salvouResposta = true;
                }

                // Retorna a data somente de perguntas efetivamente respondidas
                $temRespostaSalva = trim((string)($entity->resposta ?? '')) !== '';

                $temOcorrenciasMarcadas = in_array((int)$perguntaId, [16, 18], true)
                    && !empty(array_filter(
                        $resposta['ocorrencias'] ?? [],
                        fn($valor) => (string)$valor === '1'
                    ));

                $perguntaSomenteTexto = (int)$perguntaId === 20
                    && trim((string)($entity->observacao ?? '')) !== '';

                if (
                    $entity->modified &&
                    ($temRespostaSalva || $temOcorrenciasMarcadas || $perguntaSomenteTexto)
                ) {
                    $datasRespostas[$perguntaId] =
                        $entity->modified->format('d/m/Y H:i');
                }


                if (isset($resposta['ocorrencias'])) {
                    $idsPossiveis = $queryOcorrencias->find()
                        ->select(['id'])
                        ->where(['pergunta_id' => $perguntaId])
                        ->all()
                        ->extract('id')
                        ->toArray();

                    $OcorrenciaRelatorios->deleteAll([
                        'relatorio_id' => $id,
                        'ocorrencia_id IN' => $idsPossiveis,
                    ]);

                    // Filtra somente as ocorrências que foram de fato marcadas (valor "1")
                    $ocorrenciasMarcadas = array_filter($resposta['ocorrencias'], function ($valor) {
                        return (string)$valor === '1';
                    });

                    foreach (array_keys($ocorrenciasMarcadas) as $ocorrenciaId) {
                        $OcorrenciaRelatorios->save($OcorrenciaRelatorios->newEntity([
                            'relatorio_id' => $id,
                            'ocorrencia_id' => $ocorrenciaId,
                        ]));
                    }
                }
            }

            $isAjax = $this->request->is('ajax')
                || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest'
                || (bool)$this->request->getData('_ajax');

            if ($isAjax) {
                $contagem = $this->contarRespondidasPorDimensao($id, $queryPerguntas);

                $this->autoRender = false;
                $this->response = $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'relatorio_id' => $id,
                        'contagem' => $contagem,
                        'datas_respostas' => $datasRespostas,
                    ]));
                return $this->response;
            }

            $this->Flash->success('Respostas salvas.');

            $proximaDimensao = (int)$this->request->getData('_proxima_dimensao', $dimensao);
            $queryString = $somentePendencias ? ['?' => ['pendencias' => 1]] : [];

            if ($somentePendencias) {
                // Recalcula pendências restantes após o save
                $pendentesRestantes = $Providencia->find()
                    ->select(['pergunta_id'])
                    ->where(['relatorio_id' => $id, 'status' => 0])
                    ->all()
                    ->extract('pergunta_id')
                    ->toArray();

                if (empty($pendentesRestantes)) {
                    $this->Flash->success('Todas as pendências deste relatório foram resolvidas.');
                    return $this->redirect(['action' => 'dash-supervisor']);
                }

                $dimensoesRestantes = $queryPerguntas->find()
                    ->select(['dimensao'])
                    ->where(['id IN' => $pendentesRestantes])
                    ->distinct(['dimensao'])
                    ->orderByAsc('dimensao')
                    ->all()
                    ->extract('dimensao')
                    ->toArray();

                // Se a dimensão atual ainda tem pendência, fica nela; senão, pula pra próxima disponível
                $proximaDimensao = in_array($dimensao, $dimensoesRestantes)
                    ? $dimensao
                    : (collection($dimensoesRestantes)->filter(fn($d) => $d > $dimensao)->first() ?? min($dimensoesRestantes));
            }
            return $this->redirect(array_merge(
                ['action' => 'manterPerguntas', $proximaDimensao, $escola_id, $id],
                $queryString
            ));
            // return $this->redirect(['action' => 'manterPerguntas', $proximaDimensao, $escola_id, $id]);
        }

        //Perguntas de acordo com a dimensão
        $condicoes = ['dimensao' => $dimensao];

        if ($id != null) {
            $condicoes['created <'] = $relatorio->created;
        }

        $perguntas = $queryPerguntas->find()
            ->select(['id', 'ordem', 'descricao', 'tipo', 'opcoes', 'importancia', 'dimensao', 'created'])
            ->where($condicoes)
            ->orderByAsc('ordem')->all();

        //Monta array com ocorrencis existentes em relação a pergunta
        $ocorrencias = [];
        foreach ($perguntas as $p)
            if ($p->tipo == 'checkbox')
                $ocorrencias[$p->id] = $queryOcorrencias->find()
                    ->select(['id', 'nm_tp_ocorrencia'])
                    ->where(['pergunta_id' => $p->id])
                    ->orderBy(['CASE WHEN nm_tp_ocorrencia = "Outros" THEN 1 ELSE 0 END' => 'ASC', 'nm_tp_ocorrencia' => 'ASC'])
                    ->all();
        $perguntaIds = $perguntas->extract('id')->toArray();

        // $this->Flash->error('Não foi possível salvar as respostas.');

        if ($id) {
            $respostasSalvas = $Respostas->find()
                ->where(['relatorio_id' => $id, 'pergunta_id IN' => $perguntaIds])
                ->all()
                ->indexBy('pergunta_id')
                ->toArray();

            $ocorrenciaIdsSalvas = $OcorrenciaRelatorios->find()
                ->where(['relatorio_id' => $id])
                ->all()
                ->extract('ocorrencia_id')
                ->toArray();
        }

        $providenciasSalvas = [];

        if ($id && !empty($perguntaIds)) {
            $providenciasSalvas = $Providencia->find()
                ->where([
                    'relatorio_id' => $id,
                    'pergunta_id IN' => $perguntaIds
                ])
                ->all()
                ->indexBy('pergunta_id')
                ->toArray();
        }

        // Contagem de pendências por dimensão (para exibir badge na coluna de seções)
        $pendenciasPorDimensaoCount = [];
        if ($id) {
            $pendenciaIdsPergunta = $Providencia->find()
                ->select(['pergunta_id'])
                ->where(['relatorio_id' => $id, 'status' => 0])
                ->all()
                ->extract('pergunta_id')
                ->toArray();

            if (!empty($pendenciaIdsPergunta)) {
                $perguntasPendentesTodasDim = $queryPerguntas->find()
                    ->select(['id', 'dimensao'])
                    ->where(['id IN' => $pendenciaIdsPergunta])
                    ->all();

                foreach ($perguntasPendentesTodasDim as $pp) {
                    $pendenciasPorDimensaoCount[$pp->dimensao] = ($pendenciasPorDimensaoCount[$pp->dimensao] ?? 0) + 1;
                }
            }
        }

        // dd($pendenciasPorDimensaoCount);

        if ($this->identity?->isGestor) {
            $perguntas = $perguntas->filter(function ($p) {
                return $p->id != 34;
            });
        }

        // ----- Aplica o filtro de pendências por último, já com $respostasSalvas montado -----
        if ($somentePendencias) {
            $perguntaIdsPendentes = $Providencia->find()
                ->select(['pergunta_id'])
                ->where(['relatorio_id' => $id, 'status' => 0])
                ->all()
                ->extract('pergunta_id')
                ->toArray();

            $perguntas = $perguntas->filter(function ($p) use ($perguntaIdsPendentes) {

                return in_array($p->id, $perguntaIdsPendentes);
            });
        }

        $this->set([
            // 'escolaName' => $escolaName,
            'relatorio' => $relatorio,
            'dimensao' => $dimensao,
            // 'usuarios_lista' => $usuarios_lista,
            'perguntas' => $perguntas,
            'ocorrencias' => $ocorrencias,
            'escola_id' => $escola_id,
            'respostasSalvas' => $respostasSalvas ?? [],
            'providenciasSalvas' => $providenciasSalvas,
            'ocorrenciaIdsSalvas' => $ocorrenciaIdsSalvas ?? [],
            'somentePendencias' => $somentePendencias,
            'dimensoesComPendencia' => $dimensoesComPendencia,
            'pendenciasPorDimensao' => $pendenciasPorDimensaoCount,
            'modoSomenteLeitura' => $modoSomenteLeitura,
            'desabilitaSalvarRasc' => $desabilitaSalvarRasc,
            // 'assinaturaUsuario' => $assinaturaUsuario,
            // 'cargos' => $cargos
        ]);
    }

    private function secoesTermo($dimensao, $queryPerguntas, $relatorioId = null)
    {
        // Qtd de ocorrências por dimensão
        $Dimensoes = $this->fetchTable('Dimensao');
        $Respostas = $this->fetchTable('Respostas');
        $OcorrenciaRelatorios = $this->fetchTable('OcorrenciaRelatorios');
        $Ocorrencias = $this->fetchTable('Ocorrencias');
        $Providencia = $this->fetchTable('Providencias');

        if ($relatorioId) {
            $relatorio = $this->Relatorios->get($relatorioId);
        }

        // Total de perguntas por dimensão
        $countQuery = $queryPerguntas->find();

        $totalPorDimensao = $countQuery
            ->select(['dimensao', 'total' => $countQuery->func()->count('*')])
            ->where(
                !empty($relatorio->created)
                    ? ['created <' => $relatorio->created]
                    : []
            )
            ->groupBy(['dimensao'])
            ->orderByAsc('dimensao')
            ->all()
            ->indexBy('dimensao')
            ->toArray();

        // $dt = $query->select(['dimensao', 'total' => $query->func()->count('*')])->groupBy('dimensao')->orderByAsc('dimensao')->all()->indexBy('dimensao')->toArray();
        if ($relatorioId) {
            // $relatorio = $this->Relatorios->get($relatorioId);
            // Perguntas (id + dimensao + tipo), para cruzar com o que foi respondido
            $perguntas = $queryPerguntas->find()
                ->select(['id', 'dimensao', 'tipo', 'created'])
                ->where(['created <' => $relatorio->created])
                ->all();
        } else {
            $perguntas = $queryPerguntas->find()
                ->select(['id', 'dimensao', 'tipo'])
                ->all();
        }
        $perguntaIdsRespondidas = [];

        if ($relatorioId) {
            // Todas as respostas salvas para este relatório (radio e checkbox geram linha aqui)
            $respostasSalvas = $Respostas->find()
                ->select(['pergunta_id', 'resposta', 'observacao'])
                ->where(['relatorio_id' => $relatorioId])
                ->all()
                ->indexBy('pergunta_id')
                ->toArray();

            // Perguntas do tipo checkbox que têm ao menos 1 ocorrência marcada
            $ocorrenciaIdsMarcadas = $OcorrenciaRelatorios->find()
                ->select(['ocorrencia_id'])
                ->where(['relatorio_id' => $relatorioId])
                ->all()
                ->extract('ocorrencia_id')
                ->toArray();

            $perguntaIdsComOcorrenciaMarcada = [];
            if ($ocorrenciaIdsMarcadas) {
                $perguntaIdsComOcorrenciaMarcada = $Ocorrencias->find()
                    ->select(['pergunta_id'])
                    ->where(['id IN' => $ocorrenciaIdsMarcadas])
                    ->all()
                    ->extract('pergunta_id')
                    ->toArray();
            }

            foreach ($perguntas as $p) {
                $resposta = $respostasSalvas[$p->id] ?? null;
                $observacaoPreenchida = $resposta && trim((string)$resposta['observacao']) !== '';

                if ($p->tipo === 'checkbox') {
                    // Respondida se: observação preenchida OU alguma ocorrência marcada
                    $temOcorrenciaMarcada = in_array($p->id, $perguntaIdsComOcorrenciaMarcada);

                    if ($observacaoPreenchida || $temOcorrenciaMarcada) {
                        $perguntaIdsRespondidas[] = $p->id;
                    }
                } elseif ($p->tipo === 'texto') {
                    // Observação é a própria resposta
                    if ($observacaoPreenchida) {
                        $perguntaIdsRespondidas[] = $p->id;
                    }
                } else {
                    // radio: respondida se "resposta" está preenchido
                    if ($resposta && trim((string)$resposta['resposta']) !== '') {
                        $perguntaIdsRespondidas[] = $p->id;
                    }
                }
            }
        }

        // Contagem de respondidas por dimensão
        $respondidasPorDimensao = [];
        foreach ($perguntas as $p) {
            if (in_array($p->id, $perguntaIdsRespondidas)) {
                $respondidasPorDimensao[$p->dimensao] = ($respondidasPorDimensao[$p->dimensao] ?? 0) + 1;
            }
        }

        // Seções do Termo, vindas da tabela Dimensoes
        $dimensoesDb = $Dimensoes->find()
            ->select(['id', 'titCompleto', 'titParcial'])
            ->orderByAsc('id')
            ->all();

        $dimensoes = [];
        foreach ($dimensoesDb as $d) {
            // Tipos 1 e 5 não visualizam a dimensão 7
            if ($this->identity?->isGestor && $d->id == 7) continue;
            $dimensoes[] = (object)[
                'dimensao' => $d->id,
                'titCompleto' => $d->titCompleto,
                'titParcial' => $d->titParcial,
                'pergTotal' => $totalPorDimensao[$d->id]['total'] ?? 0,
                'pergRespondidas' => $respondidasPorDimensao[$d->id] ?? 0,
            ];
        }

        $idsDimensoes = array_map(
            fn($d) => (int)$d->dimensao,
            $dimensoes
        );

        if (!in_array((int)$dimensao, $idsDimensoes, true)) {
            $dimensao = $idsDimensoes[0] ?? 1;
        }

        $indiceAtual = array_search((int)$dimensao, $idsDimensoes, true);

        // debug($indiceAtual);
        // dd(count($idsDimensoes));

        // dd ($this->request->getQuery('pendencias'));

        $desabilitaAnt = ($indiceAtual === 0) ? 'disabled' : '';

        $desabilitaProx = ($indiceAtual === count($idsDimensoes) - 1) ? 'disabled' : '';

        // Mantém Botões Etapa anterior e posterior em seus limites
        // if ($dimensao < 1) $dimensao = 1;
        // else if ($dimensao > count($dimensoes)) $dimensao = count($dimensoes);

        // if ($dimensao <= 1) {
        //     $desabilitaAnt = 'disabled';
        //     $dimensao = 1;
        // } else if ($dimensao >= count($dimensoes)) {
        //     $desabilitaProx = 'disabled';
        //     $dimensao = count($dimensoes);
        // }

        $this->set([
            'romanos' => [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII',
                13 => 'XIII',
                14 => 'XIV',
                15 => 'XV',
                16 => 'XVI',
                17 => 'XVII',
                18 => 'XVIII',
                19 => 'XIX',
                20 => 'XX'
            ],
            'dimensoes' => $dimensoes,
            'desabilitaAnt' => $desabilitaAnt ?? '',
            'desabilitaProx' => $desabilitaProx ?? '',
        ]);
        return $dimensao;
    }

    private function contarRespondidasPorDimensao($id, $queryPerguntas)
    {
        if (!$id) {
            return [];
        }

        $Respostas = $this->fetchTable('Respostas');
        $OcorrenciaRelatorios = $this->fetchTable('OcorrenciaRelatorios');
        $Ocorrencias = $this->fetchTable('Ocorrencias');

        $perguntas = $queryPerguntas->find()
            ->select(['id', 'dimensao', 'tipo'])
            ->all();

        $respostas = $Respostas->find()
            ->select(['pergunta_id', 'resposta', 'observacao']) // <- adicionado 'observacao'
            ->where(['relatorio_id' => $id])
            ->all()
            ->indexBy('pergunta_id')
            ->toArray();

        // Mapa ocorrencia_id -> pergunta_id, pra saber a quem pertence cada ocorrência marcada
        $ocorrenciaParaPergunta = $Ocorrencias->find()
            ->select(['id', 'pergunta_id'])
            ->all()
            ->indexBy('id')
            ->toArray();

        $ocorrenciaIdsSalvas = $OcorrenciaRelatorios->find()
            ->select(['ocorrencia_id'])
            ->where(['relatorio_id' => $id])
            ->all()
            ->extract('ocorrencia_id')
            ->toArray();

        // Perguntas (checkbox) que têm ao menos 1 ocorrência marcada
        $perguntasComOcorrenciaMarcada = [];
        foreach ($ocorrenciaIdsSalvas as $ocorrenciaId) {
            $perguntaId = $ocorrenciaParaPergunta[$ocorrenciaId]->pergunta_id ?? null;
            if ($perguntaId) {
                $perguntasComOcorrenciaMarcada[$perguntaId] = true;
            }
        }

        $contagem = [];
        foreach ($perguntas as $p) {
            $r = $respostas[$p->id] ?? null;
            $temObservacao = $r && $r->observacao !== null && trim($r->observacao) !== '';

            if ($p->tipo === 'radio') {
                // Respondida somente se selecionou uma opção
                $respondida = ($r && $r->resposta !== null && $r->resposta !== '');
            } elseif ($p->tipo === 'checkbox') {
                // Ao menos 1 ocorrência marcada OU observação preenchida
                $temOcorrenciaMarcada = isset($perguntasComOcorrenciaMarcada[$p->id]);
                $respondida = $temOcorrenciaMarcada || $temObservacao;
            } elseif ($p->tipo === 'texto') {
                // Observação é a própria resposta
                $respondida = $temObservacao;
            } else {
                // Tipo desconhecido/futuro: fallback seguro, considera resposta OU observação
                $respondida = ($r && $r->resposta !== null && $r->resposta !== '') || $temObservacao;
            }

            if ($respondida) {
                $contagem[$p->dimensao] = ($contagem[$p->dimensao] ?? 0) + 1;
            }
        }

        return $contagem; // ex: [1 => 4, 2 => 0, 3 => 2, ...]
    }

    public function concluirAssinatura($relatorio_id)
    {
        $relatorio = $this->Relatorios->get($relatorio_id);

        $this->Authorization->authorize($relatorio, 'concluirAssinatura');

        if ($this->identity?->isSupervisor) {
            $relatorio->ic_rascunho = 0;
            $relatorio->id_ass_super = $this->identity?->person['id'];
        } elseif ($this->identity?->isGestor) {
            $relatorio->id_ass_dir = $this->identity?->person['id'];
            $relatorio->data_ass_dir = date('Y-m-d');
        } elseif ($this->identity?->isGestor) {
            $relatorio->id_ass_assis = $this->identity?->person['id'];
            $relatorio->data_ass_assis = date('Y-m-d');
        } elseif ($this->identity?->isAdministrador) {
            $relatorio->id_ass_sub = $this->identity?->person['id'];
            $relatorio->data_ass_sub = date('Y-m-d');
        }

        if ($this->Relatorios->save($relatorio)) {
            $this->Flash->success('Termo concluído e assinado com sucesso.');
        } else {
            $this->Flash->error('Não foi possível concluir e assinar o termo.');
        }

        if ($this->identity?->isGestor) {
            return $this->redirect(['action' => 'dashEscolas', $relatorio->place_id]);
        } elseif ($this->identity?->isSupervisor) {
            return $this->redirect(['action' => 'dashSupervisor', $this->identity?->id]);
        } elseif ($this->identity?->isAdministrador) {
            return $this->redirect(['action' => 'dashEscolas', $relatorio->place_id]);
        }
    }

    private function obterAssinaturaDoUsuario($identity, $relatorio): array
    {
        switch ((int)$this->identity?->tipo) {
            case 6:
                return [
                    'campo' => 'id_ass_dir',
                    'assinatura' => $relatorio->id_ass_dir,
                    'podeAssinar' => empty($relatorio->id_ass_dir),
                ];

            case 11:
                return [
                    'campo' => 'id_ass_super',
                    'assinatura' => $relatorio->id_ass_super,
                    'podeAssinar' => empty($relatorio->id_ass_super),
                ];

            case 3:
                return [
                    'campo' => 'id_ass_sub',
                    'assinatura' => $relatorio->id_ass_sub,
                    'podeAssinar' =>
                    empty($relatorio->id_ass_sub) &&
                        !empty($relatorio->id_ass_dir) &&
                        !empty($relatorio->id_ass_assis),
                ];

            case 6:
                return [
                    'campo' => 'id_ass_assis',
                    'assinatura' => $relatorio->id_ass_assis,
                    'podeAssinar' => empty($relatorio->id_ass_assis),
                ];

            case 3:
                return [
                    'campo' => 'id_ass_sub',
                    'assinatura' => $relatorio->id_ass_sub,
                    'podeAssinar' =>
                    empty($relatorio->id_ass_sub) &&
                        !empty($relatorio->id_ass_dir) &&
                        !empty($relatorio->id_ass_assis),
                ];

            default:
                return [
                    'campo' => null,
                    'assinatura' => null,
                    'podeAssinar' => false,
                ];
        }
    }

    private function relatorioEstaAssinado(
        $relatorio,
        bool $temDiretor,
        bool $temAssistente
    ): bool {

        // Rascunho nunca está completamente assinado
        if ((int)$relatorio->ic_rascunho !== 0) {
            return false;
        }

        // Subsecretária sempre obrigatória
        if (empty($relatorio->id_ass_sub)) {
            return false;
        }

        // Diretor obrigatório se existir na escola
        if ($temDiretor && empty($relatorio->id_ass_dir)) {
            return false;
        }

        // Assistente obrigatório se existir na escola
        if ($temAssistente && empty($relatorio->id_ass_assis)) {
            return false;
        }

        return true;
    }

    public function providencia($escola_id)
    {
        $this->Authorization->skipAuthorization();

        // $unid_escolares = TableRegistry::getTableLocator()->get('UnidEscolares');
        // $escola = $unid_escolares->get($escola_id);

        if ($this->identity?->isSupervisor) {

            $providencias = $this->Relatorios->Respostas->find()
                ->where([
                    'Respostas.status' => 0,
                    'Relatorios.place_id' => $escola_id,
                    'Relatorios.usuario_id' => $this->identity?->id
                ])
                ->orderBy(['Relatorios.data' => 'ASC', 'Respostas.pergunta_id' => 'ASC'])
                ->contain(['Relatorios', 'Perguntas'])
                ->all();
        } else {
            $providencias = $this->Relatorios->Respostas->find()
                ->where([
                    'Respostas.status' => 0,
                    'Relatorios.place_id' => $escola_id,
                    // 'Relatorios.usuario_id' => $this->identity?->id
                ])
                ->orderBy(['Relatorios.data' => 'ASC', 'Respostas.pergunta_id' => 'ASC'])
                ->contain(['Relatorios', 'Perguntas'])
                ->all();
        }

        $pendencias = $providencias;

        $this->set(compact('providencias', 'pendencias'));
    }

    public function salvarProvidencias()
    {
        // $this->authorize('update', $providencia);

        $this->Authorization->skipAuthorization();

        if ($this->request->is(['post', 'patch', 'put'])) {

            $ids = array_keys($this->request->getData('status', []));

            if (!empty($ids)) {

                $providencia = $this->Relatorios->Respostas->find()
                    ->select(['Respostas.id', 'Relatorios.place_id'])
                    ->where([
                        'Respostas.id IN' => $ids,
                        // 'Relatorios.place_id' => $escola_id,
                        // 'Relatorios.usuario_id' => $this->identity?->id
                    ])
                    ->contain(['Relatorios', 'Perguntas'])
                    ->first();

                $escolaId = $providencia->relatorio->place_id;

                $this->Relatorios->Respostas
                    ->updateQuery()
                    ->set([
                        'status' => true,
                        'modified' => new DateTime() // Atualiza manualmente a data da alteração
                        // 'usuario_id' => $this->identity?->id
                    ])
                    ->where([
                        'id IN' => $ids
                    ])
                    ->execute();

                $this->Flash->success('Providencias atualizadas com sucesso.');

                return $this->redirect(['action' => 'providencia', $escolaId]);
            } else {
                $this->Flash->error('Selecione pelo menos uma providência.');
                return $this->redirect($this->referer());
            }
        }
    }

    public function dashSupervisor()
    {
        $this->Authorization->skipAuthorization();
        $providencia = $this->fetchTable('Providencias');

        if (count($this->identity->places) == 1) {
            $escolaId = array_key_first($this->identity->places); 
            return $this->redirect(['controller' => 'Relatorios', 'action' => 'dash_escolas', $escolaId]);
        }

        $ano = date('Y');

        $itens = [];

        $pendenciasPorEscola = [];

        if ($this->identity->places) {

            $pendenciasPorEscola = $providencia->find()
                ->select([
                    'place_id' => 'Providencias.place_id',
                    'total' => $providencia->find()->func()->count('Providencias.id'),
                ])
                ->innerJoinWith('Relatorios')
                ->where([
                    'Providencias.status' => 0,
                    'Providencias.place_id IN' => array_keys($this->identity->places),
                    'Relatorios.ic_rascunho' => 0
                ])
                ->groupBy('Providencias.place_id')
                ->enableAutoFields(false)
                ->all()
                ->indexBy('place_id')
                ->toArray();
        }

        // foreach ($pendenciasPorEscola as $placeId => $dados) {
        //     debug([
        //         'place_id' => $placeId,
        //         'total_pendencias' => $dados->total,
        //     ]);
        // }

        // dd($pendenciasPorEscola);

        if ($this->identity->places) {

            $itens = $this->Relatorios->find()
                ->select([
                    'Relatorios.id',
                    'Relatorios.termo_id',
                    'Relatorios.data',
                    'Relatorios.place_id',
                    'Relatorios.ic_rascunho',
                    'Relatorios.id_ass_dir',
                    'Relatorios.id_ass_assis',
                    'Relatorios.id_ass_sub',
                ])
                ->where([
                    'Relatorios.place_id IN' => array_keys($this->identity->places),
                    'YEAR(Relatorios.created)' => $ano,
                    'Relatorios.person_id' => $this->identity?->person['id']
                ])
                // ->contain(['UnidEscolares'])
                ->orderBy(['Relatorios.id' => 'DESC'])
                ->all();
        }

        $titulos = [];

        foreach ($this->identity->places as $id => $unidade) {

            $escola = $unidade;

            $titulo = new \stdClass();

            $titulo->texto = $unidade;

            $titulo->escola_id = $id;

            $titulo->pendencias = $pendenciasPorEscola[$id]->total ?? 0;

            $titulo->itens = [];

            $titulos[$id] = $titulo;
        }

        $totalPendencias = array_sum(array_map(fn($p) => $p->total, $pendenciasPorEscola));

        foreach ($itens as $item) {


            $escolaId = $item->place_id;

            // Se ainda não existe o título dessa escola
            if (!isset($titulos[$escolaId])) {
                continue;
            }

            $relatorio = new \stdClass();

            $relatorio->id = $item->id;
            $relatorio->termo_id = $item->termo_id;
            $relatorio->data = $item->data;
            $relatorio->pendencias = $item->pendencias;
            $relatorio->id_ass_dir = $item->id_ass_dir;
            $relatorio->id_ass_assis = $item->id_ass_assis;
            $relatorio->id_ass_sub = $item->id_ass_sub;

            $relatorio->subtitulo = "Termo ";

            if (
                $item->ic_rascunho == 1
            ) {
                $relatorio->situacao = 3;
            } elseif (
                !empty($item->id_ass_dir) &&
                !empty($item->id_ass_assis) &&
                !empty($item->id_ass_sub)
            ) {
                $relatorio->situacao = 2;
            } elseif (
                empty($item->id_ass_dir) ||
                empty($item->id_ass_assis) ||
                empty($item->id_ass_sub)
            ) {
                $relatorio->situacao = 1;
            }

            // pr($relatorio->subtitulo);

            $titulos[$escolaId]->itens[] = $relatorio;
        }

        // Reindexa o array para começar em 0
        $titulos = array_values($titulos);

        $this->set(compact('titulos', 'totalPendencias'));
    }

    public function dashSubsecretaria()
    {
        $this->Authorization->skipAuthorization();
        $Providencias = $this->fetchTable('Providencias');

        $titulos = [];
        $pendenciasPorEscola = [];
        $escolasIds = [];

        foreach ($this->identity->people as $id => $people) {

            $places = $people['places'] ?? [];

            $titulos[] = [
                'texto' => $people['name'],
                'places' => $people['places'] ?? [],
                'total_places' => count($people['places'] ?? [])
            ];

            // Reune os IDs das escolas
            $escolasIds = array_merge(
                $escolasIds,
                array_keys($places)
            );
        }

        //Remove IDs duplicados
        $escolasIds = array_unique($escolasIds);

        //Consulta pendências das escolas
        if (!empty($escolasIds)) {

            $query = $Providencias->find();

            $pendenciasPorEscola = $query->select([
                'place_id' => 'Providencias.place_id',
                'total' => $query->func()
                    ->count(
                        'Providencias.id'
                    ),
            ])
                ->innerJoinWith('Relatorios')
                ->where([
                    'Providencias.status' => 0,
                    'Relatorios.place_id IN' => $escolasIds,
                    'Relatorios.place_id !=' => 0,
                    'Relatorios.ic_rascunho' => 0,
                ])
                ->groupBy('Providencias.place_id')
                ->enableAutoFields(false)
                ->all()
                ->indexBy('place_id')
                ->toArray();
        }

        // Situação de assinatura por escola (ignora rascunhos)
        $situacaoPorEscola = [];
        if (!empty($escolasIds)) {
            $relatoriosNaoRascunho = $this->Relatorios->find()
                ->select(['id', 'place_id', 'id_ass_dir', 'id_ass_assis', 'id_ass_sub'])
                ->where([
                    'Relatorios.place_id IN' => $escolasIds,
                    'Relatorios.ic_rascunho !=' => 1,
                ])
                ->all();

            foreach ($relatoriosNaoRascunho as $rel) {
                $escolaId = $rel->place_id;

                //Gestores existentes na unidade
                $temDiretor = $gestoresPorEscola[$escolaId]['diretor'] ?? false;
                $temAssistente = $gestoresPorEscola[$escolaId]['assistente'] ?? false;

                $completo = $this->relatorioEstaAssinado(
                    $rel,
                    $temDiretor,
                    $temAssistente
                );

                if (!$completo) {
                    $situacaoPorEscola[$escolaId] = 'Pendente';
                } elseif (!isset($situacaoPorEscola[$escolaId])) {
                    $situacaoPorEscola[$escolaId] = 'Assinado';
                }
            }
        }

        $this->set(compact('titulos', 'pendenciasPorEscola', 'situacaoPorEscola'));
    }

    public function dashSubEscolas($escola_id)
    {

        $ano = date('Y');
        $unid_escolares_vw = $this->fetchTable('UnidEscolaresVw');

        $escola = $unid_escolares_vw->find()->where(['id' => $escola_id])->first();
        if ($this->identity->isAdministrador) {
            $this->Authorization->skipAuthorization();

            $relatorios = $this->Relatorios->find()->where([
                'place_id' => $escola_id,
                'YEAR(data)' => $ano,
                'ic_rascunho' => 0,

            ])->contain(['UnidEscolares'])->orderBy(['Relatorios.id' => 'DESC']);

            $this->set(compact('relatorios'));
        }

        $this->set(compact('escola'));
    }
    public function dashDiretorEscolas($usuario)
    {
        $ano = date('Y');

        $unid_escolares_vw = $this->fetchTable('UnidEscolaresVw');
        $usuario_unid_escolares = $this->fetchTable('UsuarioUnidEscolares');
        $unid_escolares = $this->fetchTable('UnidEscolares');

        $connection = TableRegistry::getTableLocator()->get('Users', [
            'connectionName' => 'bdescola'
        ]);

        $funcionariosTable = TableRegistry::getTableLocator()->get('Funcionarios', [
            'connectionName' => 'dashescola'
        ]);

        $dashboardsTable = TableRegistry::getTableLocator()->get('Dashboards', [
            'connectionName' => 'dashescola'
        ]);

        $escolasTable = TableRegistry::getTableLocator()->get('DashEscolas');

        $funcionario = $funcionariosTable->find()
            ->select(['id_funcionario'])
            ->where(['rf' => $this->identity?->cd_rf])
            ->first();

        $providencias = $this->fetchTable('Providencias');

        if (!$funcionario) {
            $this->Authorization->skipAuthorization();

            $this->Flash->error('Usuário não localizado!');
            return $this->redirect($this->referer());
        } else {
            $dashboard = $dashboardsTable->find()->where(['funcionario_id' => $funcionario->id_funcionario])->all();
            $escolaIds = [];
            foreach ($dashboard as $dash) {
                $escolaIds[] = $dash->escola_id;
            }

            $escolas = $escolasTable->find()
                ->select([
                    'id_escola',
                    'nome'
                ])
                ->where(['id_escola IN' => $escolaIds])->all();

            $nomeEscolas = $escolas->extract('nome')->toList();

            $UnidEscolares = $this->fetchTable('UnidEscolares');

            $query = $UnidEscolares->find();

            $query->where(function ($exp) use ($nomeEscolas) {

                $orConditions = [];

                foreach ($nomeEscolas as $nome) {

                    $nomeTratado = preg_replace('/^E\.M\.\s*/', '', $nome);

                    $orConditions[] = [
                        "MATCH (nm_unid_escolar) AGAINST ('$nomeTratado') "
                    ];
                }

                return $exp->or($orConditions);
            });

            $query->limit(1); // Correção por Anderson
            $escolas_diretor = $query->all();

            $idsEscolas = [];

            foreach ($escolas_diretor as $escola) {
                $idsEscolas[] = $escola->id;
            }

            if ($this->identity?->isGestor) {

                $escolas = $usuario_unid_escolares->find('list', keyField: 'unid_escolare.id', valueField: 'unid_escolare.nm_unid_escolar')
                    ->where(['usuario_id' => $usuario])
                    ->contain(['UnidEscolares'])->orderBy(['prioridade = 1' => 'DESC'])
                    ->toArray();
            } elseif ($this->identity?->isAdministrador) {
                $escolas = $usuario_unid_escolares->find('list', keyField: 'unid_escolare.id', valueField: 'unid_escolare.nm_unid_escolar')
                    ->where(['unid_escolares_id' => $usuario])
                    ->contain(['UnidEscolares'])->orderBy(['prioridade = 1' => 'DESC'])
                    ->toArray();
            }
        }

        if (count($escolas) == 1) {
            if (count($escolas) === 1) {

                $id = array_keys($escolas);
                // $escola = $unid_escolares->get($escola->id);
                if (isset($escola)) {
                    $escola = $unid_escolares->get($escola->id);
                } else {
                    $escola = $unid_escolares->get($escolaIds);
                }
            }

            $this->set('escola', $escola);

            $id = array_keys($escolas);

            if ($this->identity?->isGestor) {

                $relatorios = $this->Relatorios->find()->where(['place_id IN' => $idsEscolas, 'YEAR(data)' => $ano])
                    ->contain(['UnidEscolares'])->orderBy(['Relatorios.id' => 'DESC']);
            } elseif ($this->identity?->isAdministrador) {
                $relatorios = $this->Relatorios->find()->where(['place_id IN' => $idsEscolas, 'YEAR(data)' => $ano])
                    ->contain(['UnidEscolares'])->orderBy(['Relatorios.id' => 'DESC']);
            }
        } else {
            $relatorios = $this->Relatorios->find()->where(['place_id IN' => $idsEscolas, 'YEAR(data)' => $ano])
                ->contain(['UnidEscolares'])->orderBy(['Relatorios.id' => 'DESC']);
        }

        $this->set(compact('relatorios'));

        if ($this->request->is('post')) {

            $escola = $this->request->getData('escola_id');

            $relatorios = $this->Relatorios->find()
                ->where(['place_id IN' => $escola, 'YEAR(data)' => $ano, 'ic_rascunho' => 0])
                ->contain(['UnidEscolares'])->orderBy(['Relatorios.id' => 'DESC']);

            $this->set(compact('relatorios'));
        }

        $this->Authorization->skipAuthorization();

        if ($this->identity?->isSupervisor) {
            $pendencias = $this->Relatorios->Respostas->find()
                ->where([
                    'Respostas.status' => 0,
                    'Relatorios.place_id IN' => $idsEscolas,
                    'Relatorios.usuario_id' => $this->identity?->id
                ])
                ->contain(['Relatorios', 'Perguntas'])
                ->all();
        } else {
            $pendencias = $this->Relatorios->Respostas->find()
                ->where([
                    'Respostas.status' => 0,
                    'Relatorios.place_id IN' => $idsEscolas,
                    // 'Relatorios.usuario_id' => $this->identity?->id
                ])
                ->contain(['Relatorios', 'Perguntas'])
                ->all();
        }

        $this->set(compact(
            'escolas',
            'ano',
            'pendencias'
            /** 'cont' ,'rel_final' */
        ));
    }

    public function dashRelatoriosAnteriores($ano, $escola_id)
    {
        $this->Authorization->skipAuthorization();
        $relatorio_anteriores = $this->fetchTable('RelatoriosAnt');
        $unid_escolares = $this->fetchTable('UnidEscolares');

        $relatorio_identificados = $relatorio_anteriores->find()->where(['dt_relatorio like' => '%' . $ano . '%', 'cd_unidade' => $escola_id])->orderByDesc('cd_termo');
        $escola = $unid_escolares->get($escola_id);

        $this->set(compact('relatorio_identificados', 'escola'));
    }

    public function dashEscolas($escola_id)
    {
        $this->Authorization->skipAuthorization();

        // debug($this->request->getData());
        // die;
        $escola_id = $escola_id + 0;

        // $unid_escolares = $this->fetchTable('UnidEscolares');
        $providencias = $this->fetchTable('Providencias');

        // $unidades = $this->Authorization->applyScope($unid_escolares->find(), 'index')->all();

        // $escola = $unid_escolares->get($escola_id);

        $ano = date('Y');

        // Verifica se foi solicitado mostrar somente os relatórios
        // que estão sem assinatura
        $somentePendentes = $this->request->getQuery('pendentes') == 1;

        if ($this->identity->isSupervisor) {

            $query = $this->Relatorios->find()
                ->where([
                    'Relatorios.place_id' => $escola_id,
                    'YEAR(Relatorios.data)' => $ano
                ])
                // ->contain(['Usuarios'])
                ->orderBy([
                    'Relatorios.id' => 'DESC'
                ]);

            // Se clicou em "Sem assinatura", filtra somente
            // os relatórios que ainda possuem assinatura faltando
            if ($somentePendentes) {
                $query->where([
                    'Relatorios.ic_rascunho !=' => 1,
                    'OR' => [
                        'Relatorios.id_ass_super IS' => null,
                        'Relatorios.id_ass_dir IS' => null,
                        'Relatorios.id_ass_sub IS' => null,
                        'Relatorios.id_ass_assis IS' => null,
                    ]
                ]);
            }

            $relatorios = $query->all();
        } else {
            $query = $this->Relatorios->find()
                ->where([
                    'Relatorios.place_id' => $escola_id,
                    'YEAR(Relatorios.data)' => $ano,
                    'Relatorios.ic_rascunho' => 0
                ])
                // ->contain(['Usuarios'])
                ->orderBy([
                    'Relatorios.id' => 'DESC'
                ]);

            $relatorios = $query->all();

            if ($this->identity?->isGestor) {

                $idsComPendencia = $this->idsComPendencia($relatorios->extract('id')->toList());

                foreach ($relatorios as $relatorio) {
                    $relatorio->pendencia = in_array($relatorio->id, $idsComPendencia, true) ? 'sim' : 'nao';
                }
            }
        }
        if ($this->identity->isSupervisor) {

            $providencias = $this->Relatorios->Providencias->find()
                ->where([
                    'Providencias.status' => 0,
                    'Relatorios.place_id' => $escola_id,
                    'Relatorios.person_id' => $this->identity?->person['id'],
                    'Relatorios.ic_rascunho' => 0 // Não é rascunho
                ])
                ->contain(['Relatorios', 'Perguntas'])
                ->all();
            foreach ($relatorios as $relatorio) {

                $relatorio->supervisor_pode_editar =
                    (int)$relatorio->ic_rascunho === 1
                    ||
                    (
                        empty($relatorio->id_ass_dir)
                        && empty($relatorio->id_ass_assis)
                    );
            }
        } else {
            // $providencias = $this->Relatorios->Respostas->find()
            //     ->where([
            //         'Respostas.status' => 0,
            //         'Relatorios.place_id' => $escola_id,
            //         'Relatorios.ic_rascunho' => 0 // Não é rascunho

            //     ])
            //     ->contain(['Relatorios', 'Perguntas'])
            //     ->all();

            $providencias = $this->Relatorios->Providencias->find()
                ->innerJoinWith('Relatorios')
                ->where([
                    'Providencias.status' => 0,
                    'Providencias.place_id IN' => $escola_id,
                    'Relatorios.ic_rascunho' => 0
                ])
                ->all();
        }

        $relatoriosPendentes = $this->fetchTable('Relatorios')
            ->find()
            ->where([
                'Relatorios.place_id' => $escola_id,
                'Relatorios.ic_rascunho !=' => 1,
                'OR' => [
                    'Relatorios.id_ass_super IS' => null,
                    'Relatorios.id_ass_dir IS' => null,
                    'Relatorios.id_ass_sub IS' => null,
                    'Relatorios.id_ass_assis IS' => null,
                ]
            ])
            ->count();

        $this->set('relatoriosPendentes', $relatoriosPendentes);
        $this->set('pendencias', $providencias);
        $this->set(compact('escola_id', 'ano', 'relatorios', 'somentePendentes'));
    }

    /**
     * Retorna os IDs (entre os informados) que possuem alguma resposta com status = 0.
     */
    private function idsComPendencia(array $relatorioIds): array
    {
        if (empty($relatorioIds)) {
            return [];
        }

        return $this->fetchTable('Respostas')
            ->find()
            ->select(['relatorio_id'])
            ->where([
                'Respostas.relatorio_id IN' => $relatorioIds,
                'Respostas.status' => 0,
            ])
            ->distinct(['Respostas.relatorio_id'])
            ->all()
            ->extract('relatorio_id')
            ->toList();
    }

    public function dashDiretor($id = null)
    {
        $this->Authorization->skipAuthorization();

        $connection = TableRegistry::getTableLocator()->get('Users', [
            'connectionName' => 'bdescola'
        ]);


        $funcionariosTable = TableRegistry::getTableLocator()->get('Funcionarios', [
            'connectionName' => 'dashescola'
        ]);

        $dashboardsTable = TableRegistry::getTableLocator()->get('Dashboards', [
            'connectionName' => 'dashescola'
        ]);

        $escolasTable = TableRegistry::getTableLocator()->get('DashEscolas');

        $funcionario = $funcionariosTable->find()
            ->select(['id_funcionario'])
            ->where(['rf' => $this->identity?->cd_rf])
            ->first();

        if (!$funcionario) {
            return [];
        }

        $escolaIds = $dashboardsTable->find()
            ->select(['escola_id'])
            ->where([
                'funcionario_id' => $funcionario->id_funcionario
            ])
            ->all()->extract('escola_id')->toList();
        // ->toArray();

        if (empty($escolaIds)) {
            return [];
        }

        $escolas = $escolasTable->find()->select([
            'id_escola',
            'nome'
        ])
            ->where([
                'id_escola IN' => $escolaIds
            ])
            ->all();

        $nomeEscolas = $escolas->extract('nome')->toList();

        $UnidEscolares = $this->fetchTable('UnidEscolares');

        $query = $UnidEscolares->find();

        $query->where(function ($exp) use ($nomeEscolas) {

            $orConditions = [];

            foreach ($nomeEscolas as $nome) {

                $nomeTratado = preg_replace('/^E\.M\.\s*/', '', $nome);

                $orConditions[] = [
                    "MATCH (nm_unid_escolar) AGAINST ('$nomeTratado') "
                ];
            }

            return $exp->or($orConditions);
        });

        $escolas_diretor = $query->all();

        $idsEscolas = [];

        foreach ($escolas_diretor as $escola) {
            $idsEscolas[] = $escola->id;
        }

        $ano = date('Y');

        $relatorios = $this->Relatorios->find()->where(['place_id IN' => $idsEscolas, 'YEAR(data)' => $ano])
            ->contain(['UnidEscolares'])->orderBy(['Relatorios.id' => 'DESC']);

        $this->set(compact('ano', 'relatorios'));
    }

    public function edit($id)
    {
        $relatorio = $this->Relatorios->get($id, contain: ['UnidEscolares', 'OcorrenciaRelatorios']);

        $unid_escolares = TableRegistry::getTableLocator()->get('UnidEscolares');

        $query = $unid_escolares->find();
        $query->select(['id', 'id_escola', 'sigla', 'nm_unid_escolar', 'nome_completo' => $query->func()->concat(['sigla' => 'identifier', ' ', 'nm_unid_escolar' => 'identifier'])]);
        $escola = $query->where(['id' => $relatorio->place_id])->first();

        $this->Authorization->authorize($relatorio, 'edit');

        $funcoes = TableRegistry::getTableLocator()->get('Funcoes');
        $ocorrencia = $this->fetchTable('Ocorrencias');

        $list_funcao = $funcoes->find('list', keyField: 'id', valueField: 'nm_funcao')->toArray();

        $usuarios = $this->fetchTable('Usuarios');
        $usuario_unid_escolares = $this->fetchTable('UsuarioUnidEscolares');

        $ocorrenciasRelacionadasIds = [];

        if (!empty($relatorio->ocorrencia_relatorios)) {
            $ocorrenciasRelacionadasIds = collection($relatorio->ocorrencia_relatorios)
                ->extract('ocorrencia_id')
                ->toArray();
        }

        if ($this->request->is(['post', 'put'])) {

            $relatorio = $this->Relatorios->patchEntity($relatorio, $this->request->getData(), [
                'associated' => ['OcorrenciaRelatorios']
            ]);

            $ocorrenciasManutIds = $this->request->getData('list_manutencao');
            $ocorrenciasLegIds = $this->request->getData('list_legislacao');

            $this->Relatorios->OcorrenciaRelatorios->deleteAll(['relatorio_id' => $id]);

            if (!empty($ocorrenciasManutIds)) {

                foreach ($ocorrenciasManutIds as $i => $ocorrenciasManutId) {

                    if ($ocorrenciasManutId != 0) {
                        $entity = $this->Relatorios->OcorrenciaRelatorios->newEmptyEntity();

                        $entity->relatorio_id = $id;
                        $entity->ocorrencia_id = $ocorrenciasManutId;

                        $this->Relatorios->OcorrenciaRelatorios->save($entity);
                    }
                }
            }

            if (!empty($ocorrenciasLegIds)) {

                foreach ($ocorrenciasLegIds as $i => $ocorrenciasLegId) {
                    if ($ocorrenciasLegId != 0) {
                        $entity = $this->Relatorios->OcorrenciaRelatorios->newEmptyEntity();
                        $entity->relatorio_id = $id;
                        $entity->ocorrencia_id = $ocorrenciasLegId;

                        $this->Relatorios->OcorrenciaRelatorios->save($entity);
                    }
                }
            }

            foreach ($relatorio->ocorrencia_relatorios as $i => $ocorrencia_relatorio) :

                if ($ocorrencia_relatorio->ocorrencia_id === null) {
                    unset($relatorio->ocorrencia_relatorios[$i]);
                }

            endforeach;

            $rascunho = $this->request->getData('ic_rascunho');

            if ($rascunho == '0') {
                $relatorio->id_ass_super = $relatorio->usuario_id;
            }

            if ($this->Relatorios->save($relatorio)) {

                $this->Flash->success('Relatório salvo');

                $setor_usuario = $usuario_unid_escolares->find()->where(['usuario_id' => $this->identity?->id])->first();

                return $this->redirect(['action' => 'dashEscolas', $relatorio->place_id]);
            }
        }

        $list_manutencao = $ocorrencia->find()->where(['categorias_id' => 3])->toArray();
        $list_legislacao = $ocorrencia->find()->where(['categorias_id' => 4])->toArray();

        $query = $usuarios->find('list', keyField: 'id', valueField: 'nm_usuario')->contain('UsuarioUnidEscolares')->where(['UsuarioUnidEscolares.unid_escolares_id' => $relatorio->place_id]);


        $escola_user = $this->fetchTable('Users');

        $busca_escola_users = $escola_user->find()->where(['escola_id' => $escola->id_escola])->toArray();

        if (count($busca_escola_users) > 0) {

            foreach ($busca_escola_users as $escola_user):
                $escola_usuarios[] = str_replace(array(".", ""), "", $escola_user->rf);
            endforeach;

            if (count($escola_usuarios) > 0) {
                $escola_users = $usuarios->find('list', keyField: 'id', valueField: 'nm_usuario')->where(['cd_rf IN' => $escola_usuarios])->toArray();

                if (count($escola_users) == 0) {
                    /** Aqui Salva os usuários encontrados no Banco Escola na Tabela Usuarios do bd_termo 
                     * 
                     * E Carrega a variável $escola_users com esse cadastro recém efetuado.
                     * 
                     */
                    $this->Flash->error("Não tem Equipe de Direção cadastrada no Sistema Termos. Aguardando para ver como será feito.");
                }

                $this->set(compact('escola_users'));
            }
        } else {
            $this->Flash->error("Não tem Equipe de Direção cadastrada no Sistema Escolas. ");
        }


        $this->set(compact('relatorio', 'list_funcao', 'escola_users', 'escola', 'list_manutencao', 'list_legislacao', 'ocorrenciasRelacionadasIds'));
    }

    public function visualizarPdf($id)
    {
        $this->Authorization->skipAuthorization();

        $Dimensoes = $this->fetchTable('Dimensao');
        $queryPerguntas = $this->fetchTable('Perguntas');
        $Respostas = $this->fetchTable('Respostas');
        $Ocorrencias = $this->fetchTable('Ocorrencias');
        $OcorrenciaRelatorios = $this->fetchTable('OcorrenciaRelatorios');
        $UnidEscolares = $this->fetchTable('UnidEscolares');
        $Usuarios = $this->fetchTable('Usuarios');

        $relatorio = $this->Relatorios->get($id);

        $cargoDesejado = 'Subsecretario';
        $cargoEncontrado = null;

        $jobTitles = $relatorio->administrador['employees'][0]['job_titles'] ?? [];

        foreach ($jobTitles as $cargo) {
            $titulo = trim($cargo['title'] ?? '');

            // Ignora cargos vazios
            if ($titulo === '') {
                continue;
            }

            // Guarda o primeiro cargo válido como alternativa
            if ($cargoEncontrado === null) {
                $cargoEncontrado = $titulo;
            }

            // Obtém somente a primeira palavra
            $primeiraPalavra = preg_split('/\s+/u', $titulo)[0];

            // Compara com o cargo desejado
            if (mb_strtolower($primeiraPalavra) === mb_strtolower($cargoDesejado)) {
                $cargoEncontrado = $titulo;
                break;
            }
        }
        // die;

        // dd($relatorio->administrador['employees'][0]['job_titles'][0]['title']);

        $escolaName = $UnidEscolares->find()
            ->select(['id', 'sigla', 'nm_unid_escolar'])
            ->where(['id' => $relatorio->place_id])
            ->first();

        // Todas as perguntas criadas até a data do relatório, ordenadas por dimensão e ordem
        $perguntas = $queryPerguntas->find()
            ->select(['id', 'dimensao', 'ordem', 'descricao', 'tipo', 'opcoes', 'created'])
            ->where(['created <' => $relatorio->created])
            ->orderByAsc('dimensao')
            ->orderByAsc('ordem')
            ->all();

        $perguntaIds = $perguntas->extract('id')->toArray();

        // Respostas salvas, indexadas por pergunta_id
        $respostasSalvas = $Respostas->find()
            ->where(['relatorio_id' => $id, 'pergunta_id IN' => $perguntaIds])
            ->contain(['Relatorios' => ['Providencias']])
            ->all()
            ->indexBy('pergunta_id')
            ->toArray();

        // Ocorrências marcadas (para perguntas do tipo checkbox)
        $ocorrenciaIdsSalvas = $OcorrenciaRelatorios->find()
            ->where(['relatorio_id' => $id])
            ->all()
            ->extract('ocorrencia_id')
            ->toArray();

        // Monta ocorrências disponíveis por pergunta (checkbox), com flag "marcado"
        $ocorrenciasPorPergunta = [];
        foreach ($perguntas as $p) {
            if ($p->tipo === 'checkbox') {
                $ocorrenciasPorPergunta[$p->id] = $Ocorrencias->find()
                    ->select(['id', 'nm_tp_ocorrencia'])
                    ->where(['pergunta_id' => $p->id])
                    ->orderBy(['CASE WHEN nm_tp_ocorrencia = "Outros" THEN 1 ELSE 0 END' => 'ASC', 'nm_tp_ocorrencia' => 'ASC'])
                    ->all();
            }
        }

        // Nomes das dimensões
        $dimensoesDb = $Dimensoes->find()
            ->select(['id', 'titCompleto'])
            ->orderByAsc('id')
            ->all()
            ->indexBy('id')
            ->toArray();

        // Agrupa as perguntas por dimensão, já na ordem correta (dimensao asc, ordem asc)
        $perguntasPorDimensao = [];
        foreach ($perguntas as $p) {
            $perguntasPorDimensao[$p->dimensao][] = $p;
        }
        ksort($perguntasPorDimensao); // garante que as dimensões fiquem em ordem crescente

        // Nome do usuário responsável (pergunta 35)
        $responsavelNome = null;
        if ($relatorio->responsavel_id) {
            // $responsavel = $Usuarios->find()
            //     ->select(['nm_usuario'])
            //     ->where(['id' => $relatorio->responsavel_id])
            //     ->first();
            // $responsavelNome = $responsavel->nm_usuario ?? null;
        }

        if ($relatorio->id_ass_dir != null) {
            // $diretor = $Usuarios->find()
            //     ->select(['nm_usuario'])
            //     ->where(['id' => $relatorio->id_ass_dir])
            //     ->first();

            // $this->set(compact('diretor'));
        }
        if ($relatorio->id_ass_assis != null) {
            // $assistente = $Usuarios->find()
            //     ->select(['nm_usuario'])
            //     ->where(['id' => $relatorio->id_ass_assis])
            //     ->first();
            // $this->set(compact('assistente'));
        }
        if ($relatorio->id_ass_sub != null) {
            // $subsecretario = $Usuarios->find()
            //     ->select([
            //         'Usuarios.id',
            //         'Usuarios.nm_usuario',
            //         'Usuarios.tp_usuarios_id',
            //         'TpUsuarios.id',
            //         'TpUsuarios.nm_tp_usuarios'
            //     ])
            //     ->where(['Usuarios.id' => $relatorio->id_ass_sub])
            //     // ->contain('TpUsuarios')
            //     ->first();
            // $this->set(compact('subsecretario'));
        }
        // dd($subsecretario);

        $this->set([
            'relatorio' => $relatorio,
            'escolaName' => $escolaName,
            'dimensoesDb' => $dimensoesDb,
            'perguntasPorDimensao' => $perguntasPorDimensao,
            'respostasSalvas' => $respostasSalvas,
            'ocorrenciasPorPergunta' => $ocorrenciasPorPergunta,
            'ocorrenciaIdsSalvas' => $ocorrenciaIdsSalvas,
            'responsavelNome' => $responsavelNome,
            'cargoEncontrado' => $cargoEncontrado
        ]);

        // Layout mais enxuto, sem menu/navegação, próprio para impressão
        $this->viewBuilder()->setLayout('print');
    }

    public function sign($id)
    {
        $this->viewBuilder()->setLayout('print');

        $relatorio = $this->Relatorios->get($id, contain: [
            'UnidEscolares',
            'Respostas',
            // 'Funcoes',
            'OcorrenciaRelatorios' => ['Ocorrencias'],
            'Usuarios' => ['UsuarioUnidEscolares']
        ]);

        $respostas = $this->fetchTable('Respostas')->find()
            ->where(['relatorio_id' => $id])
            ->all();

        /** Aqui colocar vários if verificando se no relatório tem o
         *  ID do Supervisor,
         *  do Diretor e
         *  do SubSecretario  */


        if ($relatorio->id_ass_super) {
            $supervisor = $this->Relatorios->Usuarios->find()->where(['id' => $relatorio->id_ass_super])->first();
            $this->set(compact('supervisor'));
        }

        if ($relatorio->id_ass_dir) {
            $diretor = $this->Relatorios->Usuarios->find()->where(['id' => $relatorio->id_ass_dir])->first();
            $this->set(compact('diretor'));
        }

        if ($relatorio->id_ass_assis) {
            $assistente = $this->Relatorios->Usuarios->find()->where(['id' => $relatorio->id_ass_assis])->first();
            $this->set(compact('assistente'));
        }

        if ($relatorio->id_ass_sub) {
            $subsecretario = $this->Relatorios->Usuarios->find()->where(['id' => $relatorio->id_ass_sub])->first();
            $this->set(compact('subsecretario'));
        }

        try {
            $this->Authorization->authorize($relatorio, 'visualizarPdf');
        } catch (ForbiddenException $e) {
            $this->Flash->error('Você não possui permissão para acessar este documento.');

            return $this->redirect(['action' => 'dash_diretor_escolas', $this->identity?->id]);
        }

        $ocorrenciasRelacionadasIds = [];

        if (!empty($relatorio->ocorrencia_relatorios)) {
            $ocorrenciasRelacionadasIds = collection($relatorio->ocorrencia_relatorios)
                ->extract('ocorrencia_id')
                ->toArray();
        }

        $funcoes = $this->fetchTable('Funcoes');
        $perguntas = $this->fetchTable('Perguntas')->find()->orderBy(['ordem' => 'ASC'])->all();
        // ->find()->orderBy(['codigo' => 'ASC'])
        // ->all();

        $respostasMap = [];
        foreach ($relatorio->respostas as $resposta) {
            $respostasMap[$resposta->pergunta_id] = $resposta;
        }

        $list_funcao = $funcoes->find('list', keyField: 'id', valueField: 'nm_funcao')->toArray();
        $list_manutencao = $this->Relatorios->OcorrenciaRelatorios->Ocorrencias->find()->where(['pergunta_id' => 16])->toArray();
        $list_legislacao = $this->Relatorios->OcorrenciaRelatorios->Ocorrencias->find()->where(['pergunta_id' => 18])->toArray();

        $this->set(compact('relatorio', 'perguntas', 'respostasMap', 'list_funcao', 'list_manutencao', 'list_legislacao', 'ocorrenciasRelacionadasIds'));
    }

    public function cancelar()
    {
        $this->request->allowMethod(['post']);

        $id = $this->request->getData('id_relatorio');

        $sucesso = false;
        $mensagem = 'Relatório não encontrado.';

        if ($id) {
            $relatorio = $this->Relatorios->get($id);

            $this->Authorization->authorize($relatorio, 'cancelar');

            $relatorio->ic_cancelado = 1;

            if ($this->Relatorios->save($relatorio)) {
                $sucesso = true;
                $mensagem = 'O relatório foi tornado sem efeito!';
            } else {
                $mensagem = 'Não foi possível atualizar o status no banco de dados.';
            }

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'sucesso' => $sucesso,
                    'mensagem' => $mensagem
                ]));
        }
    }

    public function undersign($relatorio, $usuario)
    {
        $relatorio = $this->Relatorios->get($relatorio);

        $this->Authorization->authorize($relatorio, 'undersign');

        if ($this->request->is(['get', 'post', 'put'])) {

            $usuarios = $this->fetchTable('Usuarios');

            $resultado = $usuarios->find('all')->where(['id' => $usuario])->all();

            $arquivo = $resultado->last();

            if (!file_exists(WWW_ROOT . 'files' . DS . $arquivo->cd_assinatura)) {
                $this->Flash->error("Não Tem Arquivo de Assinatura em Nossos Arquivos. Deve solicitar atualização do seu cadastro. ");
                return $this->redirect($this->referer());
            } else {

                if ($this->identity?->isAdministrador) {
                    $relatorio->id_ass_sub = $usuario;
                } elseif ($this->identity?->isGestor) {
                    $relatorio->id_ass_assis = $usuario;
                } else {
                    $relatorio->id_ass_dir = $usuario;
                }

                if ($this->Relatorios->save($relatorio)) {

                    if ($this->identity?->isAdministrador) {
                        return $this->redirect(['action' => 'dash_sub_escolas', $relatorio->place_id]);
                    }
                    return $this->redirect(['action' => 'dash_diretor_escolas', $usuario]);
                }
                $this->Flash->error("Não foi possivel Assinar.");
            }
        }

        $this->disableAutoRender();

        $this->set(compact('relatorio'));
    }

    public function relatorioPdf($id)
    {
        $this->Authorization->skipAuthorization();

        $this->viewBuilder()->setLayout('print');

        $relatorio_anteriores = $this->fetchTable('RelatoriosAnt');
        $unid_escolares = $this->fetchTable('UnidEscolares');

        $relatorio = $relatorio_anteriores->find()->where(['RelatoriosAnt.id' => $id])->contain('UnidEscolares')->first();

        $usuarios = $this->fetchTable('Usuarios');

        if ($relatorio->cd_assinatura_supervisor != '') {

            $supervisor = explode(".", $relatorio->cd_assinatura_supervisor);

            $nm_supervisor = $usuarios->find()->select(['nm_usuario'])->where(['cd_rf' => $supervisor[0]])->first();

            $this->set(compact('nm_supervisor'));
        }
        if ($relatorio->cd_assinatura_diretor != '') {

            $diretor = explode(".", $relatorio->cd_assinatura_diretor);
            $nm_diretor = $usuarios->find()->select(['nm_usuario'])->where(['cd_rf' => $diretor[0]])->first();

            $this->set(compact('nm_diretor'));
        }

        if ($relatorio->cd_assinatura_admin != '') {

            $admin = explode(".", $relatorio->cd_assinatura_admin);
            $nm_admin = $usuarios->find()->select(['nm_usuario'])->where(['cd_rf' => $admin[0]])->first();

            $this->set(compact('nm_admin'));
        }

        $this->set(compact('relatorio'));
    }

    public function pendencias($escola_id)
    {
        $this->Authorization->skipAuthorization();

        $Providencias = $this->fetchTable('Providencias');
        $Perguntas = $this->fetchTable('Perguntas');
        $Relatorios = $this->fetchTable('Relatorios');
        $UnidEscolares = $this->fetchTable('UnidEscolares');

        $escolaName = $UnidEscolares->find()
            ->select(['id', 'sigla', 'nm_unid_escolar'])
            ->where(['id' => $escola_id])
            ->first();

        $pendenciasRaw = $Providencias->find()
            ->where([
                'Providencias.place_id' => $escola_id,
                'Providencias.status' => 0
            ])
            // ->matching('Relatorios', function ($q) {
            //     return $q->where([
            //         'Relatorios.ic_rascunho' => 0
            //     ]);
            // })
            ->contain(['Relatorios'])
            ->orderByDesc('Providencias.created')
            ->all();

        // dd($pendenciasRaw);

        if ($pendenciasRaw->isEmpty()) {
            $this->Flash->success('Não há pendências em aberto para esta escola.');
            return $this->redirect($this->referer());
        }

        $relatorioIds = array_unique($pendenciasRaw->extract('relatorio_id')->toArray());

        // dd($relatorioIds);

        $perguntaIds = $pendenciasRaw->extract('pergunta_id')->toArray();

        $perguntas = $Perguntas->find()
            ->where(['id IN' => $perguntaIds])
            ->all()
            ->indexBy('id')
            ->toArray();

        $relatorios = $Relatorios->find()
            ->where(['Relatorios.id IN' => $relatorioIds, 'ic_rascunho' => 0])
            // ->contain(['Usuarios'])
            ->all()
            ->indexBy('id')
            ->toArray();

            // dd($relatorios);

        $pendencias = [];
        foreach ($pendenciasRaw as $item) {
            $pergunta = $perguntas[$item->pergunta_id] ?? null;
            // $relatorio = $relatorios[$item->relatorio_id] ?? null;

            $pendencias[] = [
                'id' => $item->id,
                'descricao' => $item->descricao,
                'created' => $item->created,
                'relatorio_id' => $item->relatorio_id,
                'pergunta_id' => $item->pergunta_id,
                'pergunta_descricao' => $pergunta->descricao ?? '(pergunta removida)',
                'dimensao' => $pergunta->dimensao ?? null,
                'data_relatorio' => $item->relatorio->data ?? null,
            ];
        }

        // $pendenciasPorRelatorio = [];
        // $menorDimensaoPorRelatorio = [];

        // foreach ($pendencias as $p) {
        //     $pendenciasPorRelatorio[$p['relatorio_id']][] = $p;
        // }

        // if (
        //     $p['dimensao'] !== null &&
        //     (!isset($menorDimensaoPorRelatorio[$p['relatorio_id']]) || $p['dimensao'] < $menorDimensaoPorRelatorio[$p['relatorio_id']])
        // ) {
        //     $menorDimensaoPorRelatorio[$p['relatorio_id']] = $p['dimensao'];
        // }

        $pendenciasPorRelatorio = [];
        $menorDimensaoPorRelatorio = [];

        foreach ($pendencias as $p) {
            $relId = $p['relatorio_id'];

            $pendenciasPorRelatorio[$relId][] = $p;

            if ($p['dimensao'] !== null) {
                $dim = (int)$p['dimensao'];

                if (!isset($menorDimensaoPorRelatorio[$relId]) || $dim < $menorDimensaoPorRelatorio[$relId]) {
                    $menorDimensaoPorRelatorio[$relId] = $dim;
                }
            }
        }

        $this->set([
            'escolaName' => $escolaName,
            'escola_id' => $escola_id,
            'relatorios' => $relatorios,
            'pendenciasPorRelatorio' => $pendenciasPorRelatorio,
            'menorDimensaoPorRelatorio' => $menorDimensaoPorRelatorio,
        ]);
    }

    public function atualizarStatusResposta()
    {
        $this->Authorization->skipAuthorization();

        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $relatorioId = (int)$this->request->getData('relatorio_id');
        $perguntaId  = (int)$this->request->getData('pergunta_id');
        $status      = (int)$this->request->getData('status');
        $resposta = $this->request->getData('resposta');
        $observacao  = trim((string)$this->request->getData('observacao'));

        $Respostas   = $this->fetchTable('Respostas');
        $Providencias = $this->fetchTable('Providencias');
        $Relatorios  = $this->fetchTable('Relatorios');

        /*
            * Status 0 = requer acompanhamento.
            * Nesse caso a observação é obrigatória.
        */
        // Validação somente ao requerer acompanhamento
        if ($status === 0) {

            $perguntaId = (int)$perguntaId;

            // Perguntas 16 e 18 possuem múltiplos checkboxes
            $possuiOcorrencias =
                $this->request->getData('possui_ocorrencias') === '1';

            // Verifica se existe uma resposta selecionada
            // O valor "0" também é uma resposta válida
            $respostaVazia =
                $resposta === null ||
                trim((string)$resposta) === '';

            // Pergunta 20 não possui alternativas
            $perguntaSomenteTexto = $perguntaId === 20;

            // Perguntas 16 e 18 aceitam ocorrências selecionadas
            $perguntaCheckbox = in_array($perguntaId, [16, 18], true);

            // Verifica se a pergunta possui uma resposta válida
            $respostaValida =
                $perguntaSomenteTexto ||
                !$respostaVazia ||
                ($perguntaCheckbox && $possuiOcorrencias);

            // 1 - Valida a resposta
            if (!$respostaValida) {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'É necessário responder à pergunta antes de requerer acompanhamento.',
                        'campo' => 'resposta'
                    ]));
            }

            // 2 - Valida a observação
            if (trim((string)$observacao) === '') {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Informe a observação antes de requerer acompanhamento.',
                        'campo' => 'observacao'
                    ]));
            }
        }

        $entity = $Respostas->find()
            ->where([
                'Respostas.relatorio_id' => $relatorioId,
                'Respostas.pergunta_id' => $perguntaId
            ])
            ->first();

        if (!$entity && $status === 0) {

            $entity = $Respostas->newEmptyEntity();

            $entity->relatorio_id = $relatorioId;
            $entity->pergunta_id = $perguntaId;
            $entity->status = 0;
            $entity->resposta = $resposta;
            $entity->observacao = $observacao;

            /**
             *  Salva a resposta para gerar o ID
             * que será utilizado na Providencias.
             */

            if (!$Respostas->save($entity)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Não foi possível criar a resposta.',
                        'errors' => $entity->getErrors()
                    ]));
            }
        }
        /*
        * Se está tentando resolver uma pendência
        * sem resposta cadastrada, retorna erro.
        */
        if (!$entity) {

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Resposta não encontrada.'
                ]));
        }

        /*
        * ---------------------------------------------------------
        * REQUER ACOMPANHAMENTO
        * ---------------------------------------------------------
        */
        if ($status === 0) {

            $relatorio = $Relatorios->find()
                ->where([
                    'Relatorios.id' => $relatorioId
                ])
                ->first();

            if (!$relatorio) {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Relatório não encontrado.'
                    ]));
            }

            $identity = $this->Authentication->getIdentity();

            /*
            * Procura providência já existente para
            * essa pergunta/relatório.
            */
            $providenciaEntity = $Providencias->find()
                ->where([
                    'Providencias.relatorio_id' => $relatorioId,
                    'Providencias.pergunta_id' => $perguntaId
                ])
                ->first();

            /*
            * Se não existir, cria.
            */
            if (!$providenciaEntity) {

                $providenciaEntity = $Providencias->newEmptyEntity();

                $providenciaEntity->relatorio_id = $relatorioId;
                $providenciaEntity->pergunta_id = $perguntaId;
                $providenciaEntity->usuario_id = $identity->id;
            }

            $providenciaEntity->place_id =
                $relatorio->place_id;

            $providenciaEntity->resposta_id =
                $entity->id;

            /*
            * AQUI salva a observação da resposta
            * dentro da Providencia.
            */
            $providenciaEntity->status = 0;
            $providenciaEntity->resolvido_em = null;
            $providenciaEntity->observacao = $observacao;

            if (!$Providencias->save($providenciaEntity)) {

                // $resolvidoEm = $providenciaEntity->resolvido_em
                //     ->format('d/m/Y H:i') ?? null;

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Não foi possível salvar a providência.',
                        'errors' => $providenciaEntity->getErrors()
                    ]));
            }

            /*
            * Só altera a resposta depois que a
            * providência foi salva corretamente.
            */
            $entity->status = 0;
            $entity->observacao = $observacao;
            if (!in_array($perguntaId, [16, 18], true)) {
                $entity->resposta = $resposta;
            }

            if (!$Respostas->save($entity)) {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Não foi possível atualizar a resposta.'
                    ]));
            }
        } elseif ($status === 2) {

            // CANCELA A PENDÊNCIA

            $providenciaEntity = $Providencias->find()
                ->where([
                    'relatorio_id' => $relatorioId,
                    'pergunta_id' => $perguntaId,
                    'status' => 0
                ])
                ->first();

            if (!$providenciaEntity) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Pendência não encontrada ou já finalizada.'
                    ]));
            }

            $providenciaEntity->status = 2;
            $providenciaEntity->observacao = 'Providencia cancelada';
            $providenciaEntity->resolvido_em = null;

            if (!$Providencias->save($providenciaEntity)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Erro ao cancelar a pendência.'
                    ]));
            }

            $entity->status = 1;

            if (!$Respostas->save($entity)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Erro ao atualizar a resposta.'
                    ]));
            }
        } else {

            // RESOLVE O ACOMPANHAMENTO

            $modoPendencias = (int)$this->request->getData('modo_pendencias');

            if ($modoPendencias !== 1) {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'A pendência só pode ser resolvida no modo Pendências.'
                    ]));
            }

            $providenciaEntity = $Providencias->find()
                ->where([
                    'relatorio_id' => $relatorioId,
                    'pergunta_id' => $perguntaId,
                    'status' => 0
                ])
                ->first();

            if (!$providenciaEntity) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Providência pendente não encontrada.'
                    ]));
            }

            $dataResolucao = new \Cake\I18n\DateTime();

            $providenciaEntity->status = 1;
            $providenciaEntity->resolvido_em = $dataResolucao;

            if (!$Providencias->save($providenciaEntity)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Não foi possível resolver a providência.',
                        'errors' => $providenciaEntity->getErrors()
                    ]));
            }

            $entity->status = 1;

            if (!$Respostas->save($entity)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Não foi possível atualizar a resposta.'
                    ]));
            }

            $resolvidoEm = $dataResolucao->format('d/m/Y H:i');
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'status' => $status,
                'resolvido_em' => $resolvidoEm ?? null,
                'respondido_em' => $entity->modified
                    ? $entity->modified->format('d/m/Y H:i')
                    : null
            ]));
    }
}
