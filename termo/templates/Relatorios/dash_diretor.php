<div class="container-fluid">

    <div class="row row-cols-sm-1 row-cols-md-2 g-4 mt-3">
        <div class="col">
            <div class="card w-100">
                <div class="card-header d-flex justify-content-center bg-green30 text-white fw-bold">
                    ESCOLAS
                </div>
                <div class="card-body d-grid gap-2">
                    <?php foreach ($usuario->escolas as $escola) : ?>

                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-2 mt-2">

                            <div class="w-100 text-wrap" title="<?= $escola->unid_escolare->sigla . ' ' . $escola->unid_escolare->nm_unid_escolar ?>">
                                <?= $this->Html->link($escola->unid_escolare->sigla . ' ' . $escola->unid_escolare->nm_unid_escolar, ['action' => 'dashDiretorEscolas', $escola->unid_escolare->id]); ?>
                            </div>
                            <div class="d-inline-flex text-nowrap gap-1">
                                <?= $this->Html->link('<i class="bi bi-arrow-up-right me-2 text-white px-1"></i> Ver Escola', ['action' => 'dashDiretorEscolas', $escola->unid_escolare->id], ['class' => 'btn btn-sm btn-success d-flex align-items-center text-white', 'escape' => false]); ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-100">
                <div class="card-header d-flex justify-content-center bg-green30 text-white fw-bold">
                    TERMOS AGUARDANDO ASSINATURA
                </div>
                <div class="card-body d-grid gap-2">
                    
                        <?php foreach ($relatorios as $relatorio) : ?>
                            <?php if ($relatorio->ic_rascunho != 1) { ?>
                                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-2 mt-2">

                                    <div class="w-100 text-wrap text-wrap">
                                        <?= 'Termo ' . $relatorio->id . ' / ' . $ano . ' - ' .

                                            $relatorio->unid_escolare->sigla . ' ' . $relatorio->unid_escolare->nm_unid_escolar ?>
                                    </div>
                                    <div class="d-inline-flex gap-2">

                                        <?php if ($relatorio->ic_prioridade == 1) { ?>
                                            <i class="bi bi-exclamation-diamond fs-5 text-red40" data-bs-toggle="tooltip" data-bs-placement="top" title="Prioridade"></i>
                                        <?php } else { ?>
                                            <p></p>
                                        <?php } ?>

                                        <?php if ($relatorio->id_ass_dir != '') { ?>
                                            <?= $this->Html->link('<i class="bi bi-check2-square fs-5 text-success"></i>', ['action' => 'sign', $relatorio->id], ['target' => '_blank', 'escape' => false]); ?>
                                        <?php } else { ?>

                                            <div class="d-inline-flex text-nowrap gap-1">
                                                <?= $this->Html->link('<i class="bi bi-file-earmark-excel me-2 text-white px-1"></i> Assinar', ['action' => 'sign', $relatorio->id], ['class' => 'btn btn-sm btn-warning d-flex align-items-center text-white', 'target' => '_blank', 'escape' => false]); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php   } ?>
                        <?php endforeach; ?>
                    
                </div>
                <div class="card-footer d-inline-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-diamond fs-5 text-red40"></i> Alta Prioridade

                    <i class="bi bi-check2-square fs-5 text-success "></i> Assinado

                    <i class="bi bi-file-earmark-excel fs-5 text-warning"></i> Não Assinado
                </div>
            </div>
        </div>
    </div>
</div>