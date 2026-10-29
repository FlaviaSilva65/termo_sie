<body style="text-align: justify">

    <?php if ($relatorio->ic_cancelado): ?>
        <div class="watermark">SEM EFEITO</div>
    <?php endif; ?>
    <h5 class="text-center">TERMO DE VISITA Nº <?= $relatorio->termo_id . '/' . date('Y') ?></h5>
    <br>
    <p>
        <span>Estive, nesta data, no exercício de minhas funções, em visita de Supervisão à </span>
        <b class="text-uppercase"> <?= $relatorio->unid_escolare->nm_unid_escolar ?></b>.
    </p>
    <?php foreach ($perguntas as $pergunta): ?>

        <?php if ($pergunta->codigo == 34 && $identity->isGestor) {
            // continue;
        }
        $resposta = $respostasMap[$pergunta->id] ?? null; ?>

        <div class="mb-2">

            <b><?= $pergunta->codigo . '- ' . h($pergunta->descricao) ?></b>

            <?php if ($pergunta->tipo == 'radio' && $resposta): ?>
                <p class="ms-2">
                <div class="border border-secondary px-2 py-1 mt-1 bg-light">
                    <?php
                    $opcoes = is_array($pergunta->opcoes)
                        ? $pergunta->opcoes
                        : json_decode($pergunta->opcoes, true);
                    echo h($opcoes[$resposta->resposta] ?? $resposta->resposta);
                    ?>
                </div>
                </p>
            <?php endif; ?>

            <?php if ($pergunta->codigo == 1): ?>
                <p>Acompanhado por: <b><?= h($relatorio->nm_atendido) ?></b>
                    — <?= h($relatorio->funco->nm_funcao ?? '') ?>
                </p>

            <?php elseif ($pergunta->codigo == 16): ?>
                <div class="row mx-0 px-0">
                    <?php foreach ($list_manutencao as $ocorrencia): ?>
                        <div class="col-3 px-0 mb-1">
                            <label>
                                <input type="checkbox" disabled
                                    <?= in_array($ocorrencia->id, $ocorrenciasRelacionadasIds) ? 'checked' : '' ?>>
                                <?= h($ocorrencia->nm_tp_ocorrencia) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($pergunta->codigo == 18): ?>
                <div class="row mx-0 px-0">
                    <?php foreach ($list_legislacao as $ocorrencia): ?>
                        <div class="col-6 px-0 mb-1">
                            <label>
                                <input type="checkbox" disabled
                                    <?= in_array($ocorrencia->id, $ocorrenciasRelacionadasIds) ? 'checked' : '' ?>>
                                <span style="font-size: 13px;"><?= h($ocorrencia->nm_tp_ocorrencia) ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (
                ($pergunta->codigo != 1 && $pergunta->codigo != 34 && $resposta && !empty($resposta->observacao)) ||
                ($pergunta->codigo == 34 && ($identity->tp_usuarios_id !== 1 && $identity->tp_usuarios_id !== 5))
            ): ?>

                <div class="border border-secondary px-2 py-1 mt-1 bg-light">
                    <small><?= ucfirst(h($resposta->observacao ?? 'Sem apontamentos.')) ?></small>
                </div>
            <?php elseif (
                $pergunta->codigo == 34 && $identity->isGestor
            ): ?>
                <div class="border border-secondary px-2 py-1 mt-1 bg-light">
                    <small><span class="px-0">Resposta não exibida - Direcionada ao Gestor Superior</span></small>
                </div>
            <?php endif; ?>
        </div>
        <br />

    <?php endforeach; ?>

    <b>Prioridade:</b><span> <?= $relatorio->ic_prioridade == 0 ? 'Normal' : 'Alta' ?></span>
    <b>Responsável estava presente?</b><span><?= $relatorio->ic_responsavel == 0 ? 'Não' : 'Sim' ?></span>
    <b>Praia Grande,</b><span><?= $relatorio->data->format('d/m/Y') ?></span>

    <div class="row row-cols-3 my-2">
        <div class="col">
            <div class="text-center no-print">
                <?php if (isset($relatorio->id_ass_super)) { ?>
                    <p class="d-flex justify-content-center mb-0">
                        <?= $this->Html->image('../files/' . $supervisor->cd_assinatura, ['height' => '55']) ?>
                    </p>


                    <hr class="my-0">
                <?php } else { ?>
                    <?= $this->Html->link(
                        '<i class="bi bi-check2 me-2 text-white px-1"></i> Assinar',
                        ['action' => 'undersign', $relatorio->id, $identity->id],
                        ['class' => 'btn btn-sm btn-success', 'target' => '_blank', 'escape' => false]
                    ); ?>
                    <hr class="mt-4 my-0">
                <?php } ?>

                <p class="d-flex justify-content-center">Supervisor (a) </p>
            </div>
        </div>


        <div class="col">
            <div class="text-center no-print">
                <?php if ($relatorio->id_ass_dir) { ?>
                    <p class="d-flex justify-content-center mb-0">
                        <?= $this->Html->image('../files/' . $diretor->cd_assinatura, ['height' => '55']) ?>
                    </p>
                    <hr class="my-0">
                <?php } elseif ($identity->isGestor) { ?>
                    <?= $this->Html->link(
                        '<i class="bi bi-check2 me-2 text-white px-1"></i> Assinar',
                        ['action' => 'undersign', $relatorio->id, $identity->id],
                        ['class' => 'btn btn-sm btn-success my-2', 'escape' => false]
                    ); ?>
                    <hr class="mt-2 my-0">
                <?php } else { ?>
                    <p class="d-flex justify-content-center mb-0 ">
                        <?= $this->Html->image('../files/no-foto.jpg', ['height' => '55']) ?>
                    </p>
                    <hr class="my-0">
                <?php } ?>
                <p class="d-flex justify-content-center">Diretor (a)</p>
            </div>
            <div class="d-flex justify-content-center d-none persistent">
                <p class="d-flex justify-content-center mb-0 ">
                    <?= $this->Html->image('../files/no-foto.jpg', ['height' => '55']) ?>
                </p>
                <hr class="my-0">
                <p class="d-flex justify-content-center">Diretor (a)</p>
            </div>
        </div>

        <div class="col">
            <div class="text-center no-print">
                <?php if ($relatorio->id_ass_assis) { ?>
                    <p class="d-flex justify-content-center mb-0">
                        <?= $this->Html->image('../files/' . $assistente->cd_assinatura, ['height' => '55']) ?>
                    </p>
                    <hr class="my-0">
                <?php } elseif ($identity->isGestor) { ?>
                    <?= $this->Html->link(
                        '<i class="bi bi-check2 me-2 text-white px-1"></i> Assinar',
                        ['action' => 'undersign', $relatorio->id, $identity->id],
                        ['class' => 'btn btn-sm btn-success my-2', 'escape' => false]
                    ); ?>
                    <hr class="mt-2 my-0">
                <?php } else { ?>
                    <p class="d-flex justify-content-center mb-0 ">
                        <?= $this->Html->image('../files/no-foto.jpg', ['height' => '55']) ?>
                    </p>
                    <hr class="my-0">
                <?php } ?>
                <p class="d-flex justify-content-center">Assistente (a)</p>
            </div>
            <div class="d-flex justify-content-center d-none persistent">
                <p class="d-flex justify-content-center mb-0 ">
                    <?= $this->Html->image('../files/no-foto.jpg', ['height' => '55']) ?>
                </p>
                <hr class="my-0">
                <p class="d-flex justify-content-center">Assistente (a)</p>
            </div>
        </div>

    </div>

    <div class="row row-cols-1">
        <div class="col d-flex justify-content-center">
            <div class=" col-4 text-center no-print">
                <?php if ($relatorio->id_ass_sub) { ?>
                    <p class="d-flex justify-content-center mb-0">
                        <?= $this->Html->image('../files/' . $subsecretario->cd_assinatura, ['height' => '55']) ?>
                    </p>
                    <hr class="my-0 w-100">
                <?php } elseif ($identity->isSupervisor && !$relatorio->id_ass_sub) { ?>
                    <?= $this->Html->link(
                        '<i class="bi bi-check2 me-2 text-white px-1"></i> Assinar',
                        ['action' => 'undersign', $relatorio->id, $identity->id],
                        ['class' => 'btn btn-sm btn-success my-2', 'escape' => false]
                    ); ?>
                    <hr class="mt-2 my-0">
                <?php } else {  ?>
                    <p class="d-flex justify-content-center mb-0 ">
                        <?= $this->Html->image('../files/no-foto.jpg', ['height' => '55']) ?>
                    </p>
                    <hr class="my-0 w-100">
                <?php } ?>
                <p>Subsecretário (a)</p>
            </div>


            <div class="d-flex justify-content-center d-none persistent">
                <p class="d-flex justify-content-center mb-0 ">
                    <?= $this->Html->image('../files/no-foto.jpg', ['height' => '55']) ?>
                </p>
                <hr class="my-0 w-100">
                <p class="d-flex justify-content-center">Subsecretário (a)</p>
            </div>

        </div>

    </div>
</body>