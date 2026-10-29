<?php

namespace App\Policy;

use App\Model\Entity\Relatorio;
use App\Policy\Support\AcessoUnidades;
use Authentication\Identity;
use Authentication\IdentityInterface as AuthenticationIdentityInterface;
use Authorization\IdentityInterface;
use Cake\ORM\TableRegistry;

class RelatorioPolicy
{
    private const SUPERVISOR = 2;   // cria, altera e assina (id_ass_super)

    private function tipo(IdentityInterface $identity): int
    {
        return (int)$identity->tp_usuarios_id;
    }

    public function isAuthor(IdentityInterface $identity, Relatorio $relatorio): bool
    {
        return (int)$relatorio->usuario_id === (int)$identity->id;
    }

    /** 1 e 5: só as unidades vinculadas. 4 e 8: todas. */
    private function temAcessoUnidade(IdentityInterface $identity, int $unidadeId): bool
    {
        $tipo = $this->tipo($identity);

        if (in_array($tipo, [4, 8], true)) {
            return true;
        }

        // if (in_array($tipo, [1, 5], true)) {
        //     return in_array($unidadeId, AcessoUnidades::ids($identity), true);
        // }

        return false;
    }

    public function canAdd(IdentityInterface $usuario, Relatorio $relatorio)
    {
        return true;
    }

    // public function canManterPerguntas(IdentityInterface $usuario, Relatorio $relatorio)
    // {
    //     return true;
    // }

    public function canSecoesTermo(IdentityInterface $usuario, Relatorio $relatorio)
    {
        return true;
    }

    public function canAddNew(IdentityInterface $usuario, Relatorio $relatorio)
    {
        return true;
    }


    public function canDashSupervisor(IdentityInterface $identity, Relatorio $relatorios)
    {
        return $identity->id == $relatorios->usuario_id;
        // $this->isAuthor($identity, $relatorios);
    }

    public function canDashSubEscolas(IdentityInterface $identity)
    {
        if ($identity->isSupervisor) {
            return true;
        }
    }

    public function canDashDiretorEscolas(IdentityInterface $identity, Relatorio $relatorios)
    {
        // debug($identity);
        // die;
        foreach ($identity->escolas as $escola)
            if ($escola->unid_escolare->id == $relatorios->unid_escolar_id) {
                return true;
            }
        return false;
    }





    public function canEdit(IdentityInterface $identity, Relatorio $relatorio)
    {
        return $identity->id == $relatorio->usuario_id;
    }


    public function canSign(IdentityInterface $identity, Relatorio $relatorio)
    {
        $supervisor = $identity->id == $relatorio->usuario_id;

        if ($supervisor || $identity->isSupervisor || $identity->isGestor) return true;

        foreach ($identity->escolas as $escola)
            if ($escola->unid_escolare->id == $relatorio->unid_escolar_id) {
                return true;
            }

        // if ($identity->id == $relatorio->usuario_id) {
        //     return true;
        // }

        // if(!empty($identity->usuario_unid_escolares->unid_escolares) && $relatorio->unid_escolar_id){
        //     $userUnidadeIds = collection($identity->usuario_unid_escolares->unid_escolares)->extract('id')->toList();
        //     return in_array($relatorio->unid_escolar_id, $userUnidadeIds);
        // }

        return false;
    }

    public function canCancelar(IdentityInterface $identity, Relatorio $relatorio)
    {
        if ($identity->isSupervisor) {
            return true;
        }
        return false;
    }

    public function canVisualizarPdf(IdentityInterface $identity, Relatorio $relatorio)
    {
        if ($identity) {
            return true;
        }
        return false;
    }

    public function canUndersign(IdentityInterface $identity, Relatorio $relatorio)
    {
        // $diretor = $identity->id == $relatorio->usuario_id;

        if ($identity->isSupervisor || $identity->isGestor) {
            return true;
        }

        foreach ($identity->escolas as $escola)
            if ($escola->unid_escolare->id == $relatorio->unid_escolar_id) {
                return true;
            }
        return false;
    }

    public function canManterPerguntas(IdentityInterface $identity, Relatorio $relatorio)
    {
        // return $identity->id == $relatorio->usuario_id;
        return true;
    }

    public function canConcluirAssinatura(IdentityInterface $identity, Relatorio $relatorio): bool
    {

        // $tipoUsuario = (int)$user->tp_usuarios_id;

        // Supervisor: assina só durante o rascunho
        if ($identity->isSupervisor) {
            return $relatorio->ic_rascunho == 1 && empty($relatorio->id_ass_super);
        }

        // Enquanto está em rascunho, mais ninguém assina
        if ($relatorio->ic_rascunho == 1) {
            return false;
        }

        // Diretor
        if ($identity->isDiretor) {
            return empty($relatorio->id_ass_dir);
        }

        // Assistente
        if ($identity->isAssistente) {
            return empty($relatorio->id_ass_assis);
        }

        // Subsecretário / Adjunto
        if ($identity->isAdministrador) {
            if (!empty($relatorio->id_ass_sub)) {
                return false;
            }

            [$temDiretor, $temAssistente] = $this->gestoresExistentes($relatorio);

            $dirOk    = !$temDiretor    || !empty($relatorio->id_ass_dir);
            $assisOk  = !$temAssistente || !empty($relatorio->id_ass_assis);

            return ($temDiretor || $temAssistente) && $dirOk && $assisOk;
        }

        return false;
    }

    private function obterRfListDaUnidade($unidade)
    {
        $unidEscolares = TableRegistry::getTableLocator()
            ->get('UnidEscolares')
            ->find()
            ->select(['id', 'id_escola'])
            ->where(['id' => $unidade])
            ->first();

        if (!$unidEscolares) {
            return [];
        }

        // 2. Busca no banco externo, na tabela de relacionamento Dashboards,
        //    os funcionario_id vinculados a essa escola
        $funcionarioIds = TableRegistry::getTableLocator()
            ->get('Dashboards')
            ->find()
            ->select(['funcionario_id'])
            ->where(['escola_id' => $unidEscolares->id_escola])
            ->all()
            ->extract('funcionario_id')
            ->toArray();

        if (empty($funcionarioIds)) {
            return [];
        }

        // 3. Busca, na tabela externa Funcionarios, quem é Diretor (2) ou Assistente (3)
        $funcionarios = TableRegistry::getTableLocator()
            ->get('Funcionarios')
            ->find()
            ->select(['rf'])
            ->where([
                'id_funcionario IN' => $funcionarioIds,
                'funcao IN' => [2, 3],
            ])
            ->all();

        // 4. Retorna só a lista de cd_rf
        return $funcionarios->extract('rf')->toArray();
    }

    /**
     * Retorna [temDiretor, temAssistente] para a unidade do relatório,
     * consultando o banco externo de gestores.
     */
    private function gestoresExistentes(Relatorio $relatorio): array
    {
        $rfList = $this->obterRfListDaUnidade($relatorio->unid_escolar_id); // sua regra atual de montar $rf_list

        if (empty($rfList)) {
            return [false, false];
        }

        $tipoGestor = TableRegistry::getTableLocator()
            ->get('Usuarios')
            ->find()
            ->select(['tp_usuarios_id'])
            ->where([
                'cd_rf IN' => $rfList,
                'tp_usuarios_id IN' => [1, 5],
            ])
            ->all()
            ->extract('tp_usuarios_id')
            ->toArray();

        $temDiretor    = in_array(1, $tipoGestor);
        $temAssistente = in_array(5, $tipoGestor);

        return [$temDiretor, $temAssistente];
    }
}
