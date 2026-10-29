<body style="text-align: justify">

    <?php if ($relatorio->ic_cancelado): ?>
        <div class="watermark">SEM EFEITO</div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3 d-print-none">
        <?php if (is_null($relatorio->ic_cancelado) && $identity->isSupervisor && !empty($relatorio->id_ass_dir)) : ?>
            <button class="btn btn-sm btn-danger rounded-pill shadow"
                onclick="cancelar('<?= $relatorio->id ?>','<?= $this->Url->build(['controller' => 'Relatorios', 'action' => 'cancelar']) ?>', '<?= $this->request->getAttribute('csrfToken') ?>')">
                <i class="bi bi-file-earmark-x me-2"></i>
                Tornar sem efeito
            </button>
        <?php endif; ?>

        <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill shadow">
            <i class="bi bi-printer me-2"></i>Imprimir / Salvar PDF
        </button>
    </div>

    <h4 class="fw-bold text-primary mb-1">
        TERMO DE SUPERVISÃO Nº <?= str_pad($relatorio->termo_id, 3, '0', STR_PAD_LEFT) ?>/<?= $relatorio->data->format('Y') ?>
    </h4>
    <p class="text-secondary mb-0">
        <?php
        $escolaName = $identity->places[$relatorio->place_id];
        $supervisor = $relatorio->supervisor['name'] ?? '-';
        ?>
        <?= h($escolaName) ?>
    </p>
    <p class="text-secondary mb-0">
        Data da Visita: <?= h($relatorio->data->format('d/m/Y')) ?>
    </p>
    <?php if ($responsavelNome): ?>
        <p class="text-secondary mb-0">
            Responsável pelo acompanhamento: <?= h($responsavelNome) ?>
        </p>
    <?php endif; ?>
    <p class="text-secondary mb-0">
        Supervisor(a): <?= h($supervisor ?? '') ?>
    </p>

    <!-- Cabeçalho do Termo -->
    <div class="text-center mb-4">
    </div>
    <hr>
    <!-- Perguntas agrupadas por dimensão, em ordem -->
    <?php foreach ($perguntasPorDimensao as $dimensaoId => $perguntas): ?>
        <?php if (
            $identity->isGestor && (int) $dimensaoId === 7
        ) {
            continue;
        } ?>
        <div class="mb-4">
            <h5 class="text-primary fw-bold border-bottom pb-2 mb-3">
                DIMENSÃO <?= h($dimensaoId) ?> —
                <?= mb_strtoupper($dimensoesDb[$dimensaoId]->titCompleto ?? '') ?>
            </h5>

            <?php foreach ($perguntas as $key => $p): ?>
                <?php $respostaSalva = $respostasSalvas[$p->id] ?? null; ?>

                <div class="mb-3 pb-2 border-bottom border-light">
                    <p class="fw-semibold mb-1">
                        <?= ($key + 1) . '. ' . h($p->descricao) ?>
                    </p>

                    <?php if ($p->tipo === 'checkbox'): ?>
                        <div class="ms-3">
                            <?php foreach ($ocorrenciasPorPergunta[$p->id] ?? [] as $ocorrencia): ?>
                                <?php $marcado = in_array($ocorrencia->id, $ocorrenciaIdsSalvas); ?>

                                <?php if ($marcado): ?>
                                    <span class="badge bg-secondary me-1 mb-1">
                                        <i class="bi bi-check2-circle"></i>
                                        <?= h($ocorrencia->nm_tp_ocorrencia) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($p->tipo === 'radio'): ?>

                        <?php
                        // Recupera as opções cadastradas na pergunta
                        $opcoes = json_decode($p->opcoes ?? '{}', true);

                        // Garante que as opções sejam um array
                        $opcoes = is_array($opcoes) ? $opcoes : [];

                        // Recupera o valor salvo da resposta
                        $valorResposta = $respostaSalva?->resposta;

                        // Converte o valor para a descrição correspondente
                        if ($valorResposta !== null && $valorResposta !== '') {

                            $textoResposta = $opcoes[(string)$valorResposta]
                                ?? [
                                    '1' => 'Sim',
                                    '0' => 'Não'
                                ][(string)$valorResposta]
                                ?? (string)$valorResposta;
                        } else {
                            $textoResposta = 'Não respondida';
                        }
                        ?>

                        <p class="ms-3 mb-0">
                            <strong>Resposta:</strong>
                            <?= h($textoResposta) ?>
                        </p>

                    <?php elseif ($p->tipo === 'data'): ?>
                        <p class="ms-3 mb-0">
                            <?= h($relatorio->data->format('d/m/Y')) ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($respostaSalva->observacao)): ?>
                        <p class="ms-3 mb-0 text-secondary fst-italic">
                            Obs: <?= h($respostaSalva->observacao) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <!-- Assinaturas -->
    <div class="row d-flex justify-content-center">
        <!--- Supervisor -->
        <?php if ($relatorio->id_ass_super != null) { ?>
            <div class="col-4 mb-4 text-center">
                <div class="d-flex p-1 alert alert-light shadow" style="height:3.5em;">
                    <?= $this->Html->image('logo_ass_azul.svg', ['class' => 'h-100 pe-2']) ?>
                    <div class="my-2 border-end border-3 border-dark-primary rounded-pill"></div>
                    <div class="ps-2 py-1">
                        <!-- Somente 1º e 2º nome -->
                        <p class="fs-7 fw-bold text-dark-primary text-nowrap mb-0 lh-1"><?= $supervisor ?></p>
                        <div class="my-1 border-bottom border-3 border-dark-primary rounded-pill"></div>
                        <p class="fs-8 text-dark mb-0 text-nowrap">Supervisor(a)r</p>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-4 mt-5 pt-2 text-center">
                <div class="border-top border-dark pt-1">Supervisor(a)</div>
            </div>
        <?php } ?>
        <!--- Diretor -->
        <?php if ($relatorio->id_ass_dir != null) { ?>
            <div class="col-4 text-center">
                <div class="d-flex p-1 alert alert-light shadow" style="height:3.5em;">
                    <?= $this->Html->image('logo_ass_azul.svg', ['class' => 'h-100 pe-2']) ?>
                    <div class="my-2 border-end border-3 border-dark-primary rounded-pill"></div>
                    <div class="ps-2 py-1">
                        <!-- Somente 1º e 2º nome -->
                        <p class="fs-7 fw-bold text-dark-primary text-nowrap mb-0 lh-1"><?= h(nomeResumido($relatorio->diretor['name'])) ?></p>
                        <div class="my-1 border-bottom border-3 border-dark-primary rounded-pill"></div>
                        <p class="fs-8 text-dark mb-0 text-nowrap">Diretor(a)</p>
                    </div>
                </div>
            </div>
        <?php } elseif ($relatorio->id_ass_sub == null) { ?>
            <div class="col-4 mt-5 pt-2 text-center">
                <div class="border-top border-dark pt-1">Diretor(a)</div>
            </div>
        <?php } ?>

        <!--- Assistente -->

        <?php if ($relatorio->id_ass_assis != null) { ?>
            <div class="col-4 text-center">
                <div class="d-flex p-1 alert alert-light shadow" style="height:3.5em;">
                    <?= $this->Html->image('logo_ass_azul.svg', ['class' => 'h-100 pe-2']) ?>
                    <div class="my-2 border-end border-3 border-dark-primary rounded-pill"></div>
                    <div class="ps-2 py-1">
                        <!-- Somente 1º e 2º nome -->
                        <p class="fs-7 fw-bold text-dark-primary text-nowrap mb-0 lh-1"><?= h(nomeResumido($relatorio->assistente['name'])) ?></p>
                        <div class="my-1 border-bottom border-3 border-dark-primary rounded-pill"></div>
                        <p class="fs-8 text-dark mb-0 text-nowrap">Assistente</p>
                    </div>
                </div>
            </div>
        <?php } elseif ($relatorio->id_ass_sub == null) { ?>
            <div class="col-4 mt-5 pt-2 text-center">
                <div class="border-top border-dark pt-1">Assistente</div>
            </div>
        <?php } ?>
        <!--- Subsecretária ou Adjunto -->
        <?php if ($relatorio->id_ass_sub != null) { ?>
            <div class="col-4 text-center">
                <div class="d-flex p-1 alert alert-light shadow" style="height:3.5em;">
                    <?= $this->Html->image('logo_ass_azul.svg', ['class' => 'h-100 pe-2']) ?>
                    <div class="my-2 border-end border-3 border-dark-primary rounded-pill"></div>
                    <div class="ps-1 py-1">
                        <!-- Somente 1º e 2º nome -->
                        <p class="fs-7 fw-bold text-dark-primary text-nowrap mb-0 lh-1"><?= h(nomeResumido($relatorio->administrador['name'])) ?></p>
                        <div class="my-1 border-bottom border-3 border-dark-primary rounded-pill"></div>
                        <p class="fs-8 text-dark mb-0 text-nowrap"><?= h($cargoEncontrado) ?? 'Cargo não informado' ?></p>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-4 mt-5 pt-2 text-center">
                <div class="border-top border-dark pt-1">Subsecretário(a)</div>
            </div>
        <?php } ?>
    </div>


</body>
<?php
function nomeResumido($nome)
{
    $nome = trim($nome ?? '');

    if (mb_strlen($nome) > 26) {
        $partes = preg_split('/\s+/', $nome);

        // Se tiver pelo menos 3 nomes
        if (count($partes) >= 3) {
            return $partes[0] . ' ' . $partes[1] . ' ' . end($partes);
        }
    }

    return $nome;
}
?>