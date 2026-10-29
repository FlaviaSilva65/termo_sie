<div class="col-12 mx-auto d-flex my-5 justify-content-between align-items-stretch flex-wrap">


    <!-- Aguardando assinatura -->
    <!-- r_ic => se é rascunho ou não -->
    <?= $this->element('card', ['titulo' => 'AGUARDANDO ASSINATURA', 'tit_icone' => 'clock', 'cor' => true, 'action' => 'sign', 'r_ic' => 0, 'ass_sub' => '', 'col' => 'pe-xl-4', 'num_col' => 'col-lg-5 col-xl-6']); ?>
    <!-- Racunho -->
    <?php if (isset($relatorio_rascunho)) {
        $this->element('card', ['titulo' => 'RASCUNHOS', 'tit_icone' => 'pencil-square', 'cor' => false, 'action' => 'add', 'r_ic' => 1, 'ass_sub' => false, 'col' => 'px-xl-4', 'num_col' => 'col-lg-5 col-xl-4']);
    } ?>

    <?= $this->element('card', ['titulo' => 'FINALIZADOS', 'tit_icone' => 'journal-check', 'cor' => false, 'action' => 'sign', 'r_ic' => 0, 'ass_sub' => true, 'col' => 'ps-xl-4', 'num_col' => 'col-lg-5 col-xl-6', 'icone_btn' => 'binoculars', 'cor_btn' => 'success']); ?>

</div>


<!-- <div class="row row-cols-sm-1 row-cols-md-3 g-4 mt-3">
            <div class="col">
                <div class="card w-100">
                    <div class="card-header d-flex justify-content-center bg-success text-white ">
                        <i class="bi bi-journal-check fs-6 me-2"></i> TERMOS FINALIZADOS
                    </div>
                    <div class="card-body d-grid gap-2">
                        <?php foreach ($relatorios as $relatorio) : ?>
                            <?php if ($relatorio->ic_rascunho != 1 && $relatorio->id_ass_dir != '') { ?>

                                <div class="d-flex" style="height: 36px;">
                                    <div class=" d-inline-flex text-nowrap gap-1">
                                        <?php if ($relatorio->ic_prioridade == 1) { ?>

                                            <i class="bi bi-exclamation-diamond fs-4 me-2 text-red40" data-bs-toggle="tooltip" data-bs-placement="top" title="Alta Prioridade"></i>
                                        <?php } ?>
                                    </div>
                                    <div class="w-100 text-wrap gap-1 mt-1">
                                        <?= 'Termo ' . $relatorio->termo_id  ?>
                                    </div>
                                    <div class="d-inline-flex text-nowrap gap-1">
                                        <?= $this->Html->link('<i class="bi bi-eyeglasses fs-6 me-2 text-white px-1"></i> Visualizar', ['action' => 'sign', $relatorio->id], ['class' => 'btn btn-sm btn-success d-flex align-items-center py-0 text-white', 'target' => '_blank', 'escape' => false]); ?>
                                    </div>
                                </div>

                            <?php   } ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card w-100">
                    <div class="card-header d-flex justify-content-center bg-success text-white ">
                        <i class="bi bi-hourglass-top me-2"></i> TERMOS AGUARDANDO ASSINATURA
                    </div>

                    <div class="card-body d-grid gap-2">
                        <?php if (is_object($relatorios_s_assin)) { ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Termo</th>
                                        <th>Criado em:</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($relatorios_s_assin as $relatorio) : ?>
                                        <tr>
                                            <td><?= $relatorio->ic_prioridade == 1 ? '<i class="bi bi-exclamation-diamond fs-4 me-2 text-red40" data-bs-toggle="tooltip" data-bs-placement="top" title="Alta Prioridade"></i>' : ''; ?></td>
                                            <td>
                                                <?php if (!property_exists($relatorio, 'verdadeiro')) { ?>
                                                    <?= $this->Html->link(str_pad($relatorio->termo_id, 2, "0", STR_PAD_LEFT) . '/' . $relatorio->data->i18nFormat("Y"), ['action' => 'sign', $relatorio->id]); ?>
                                                <?php } else { ?>
                                                    <?= 'Sem relatórios' ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <span><?= $relatorio->data ?></span>
                                            </td>
                                            <td style="width: 36px;">
                                                <?= $this->Html->link(
                                                    '<i class="bi bi-pencil-square fs-6 me-2"></i> Visualizar',
                                                    ['action' => 'sign', $relatorio->id],
                                                    ['class' => $relatorio->ic_prioridade == 1 ? 'btn btn-sm btn-danger d-flex align-items-center me-1 py-0' : 'btn btn-sm btn-warning text-white d-flex align-items-center me-1 py-0', 'style' => "height: 36px;", 'target' => '_blank',  'escape' => false]
                                                ); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <div class="col-10 d-flex justify-content-start ps-2">
                                <?= $relatorios_s_assin ?>
                            </div>
                        <?php  } ?>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card w-100">
                    <div class="card-header d-flex justify-content-center bg-success text-white ">
                        <i class="bi bi-pencil-square me-2"></i>RASCUNHOS
                    </div>

                    <div class="card-body d-grid gap-3">
                        <?php if (is_object($relatorio_rascunho)) { ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Termo</th>
                                        <th>Criado em:</th>
                                        <th>Modificado em:</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($relatorio_rascunho as $relatorio) : ?>
                                        <tr>
                                            <td>
                                                <?php if (!property_exists($relatorio, 'verdadeiro')) { ?>
                                                    <?= $this->Html->link(str_pad($relatorio->termo_id, 2, "0", STR_PAD_LEFT) . '/' . $relatorio->data->i18nFormat("Y"), ['action' => 'add', $relatorio->id]); ?>
                                                <?php } else { ?>
                                                    <?= 'Sem relatórios' ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <span><?= $relatorio->data ?></span>
                                            </td>
                                            <td>
                                                <span><?= $relatorio->modified->i18nFormat("dd/MM/Y") ?></span>
                                            </td>
                                            <td style="width: 36px;">
                                                <?= $this->Html->link(
                                                    '<i class="bi bi-pencil-square fs-6 me-2 px-1 "></i> Editar',
                                                    ['action' => 'add', $relatorio->id],
                                                    ['class' => $relatorio->ic_prioridade == 1 ? 'btn btn-sm btn-danger d-flex align-items-center me-1 py-0' : 'btn btn-sm btn-warning text-white d-flex align-items-center me-1 py-0', 'escape' => false]
                                                ); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <div class="col-10 d-flex justify-content-start ps-2">
                                <?= $relatorio_rascunho ?>
                            </div>
                        <?php  } ?>
                    </div>

                </div>
            </div>
        </div> -->