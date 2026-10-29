<?php if (isset($relatorios)) { ?>
    <div class="col-11 mx-auto d-flex my-5 justify-content-between align-items-stretch flex-wrap">

        <!-- Aguardando assinatura -->
        <!-- r_ic => se é rascunho ou não -->

        <?= $this->element('card', ['titulo' => 'AGUARDANDO ASSINATURA', 'tit_icone' => 'clock', 'cor' => true, 'action' => 'sign', 'r_ic' => 0, 'ass_sub' => '', 'num_col' => 'col-xl-6 pe-xl-2']); ?>

        <!-- Racunho -->
        <?= $this->element('card', ['titulo' => 'FINALIZADOS', 'tit_icone' => 'journal-check', 'cor' => false, 'action' => 'sign', 'r_ic' => 0, 'ass_sub' => true, 'col' => 'ps-xl-4', 'num_col' => ' col-xl-6 ps-xl-2', 'icone_btn' => 'binoculars', 'cor_btn' => 'success']); ?>
    </div>
<?php } ?>

<script>
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
</script>


<!-- <div class="container-fluid">
    <div class="row row-cols-sm-1 row-cols-md-2 g-4 mt-3">

        <div class="col">
            <div class="card w-100">
                <div class="card-header d-flex justify-content-center bg-success text-white ">
                    <i class="bi bi-journal-check fs-6 me-2"></i> TERMOS FINALIZADOS
                </div>
                <div class="card-body d-grid gap-2">

                    <?php // foreach ($relatorios as $relatorio) : 
                    ?>
                        <?php // if ($relatorio->id_ass_super != '' && $relatorio->id_ass_dir != '') { 
                        ?>

                            <div class="d-flex">
                                <div class="w-100 text-truncate">
                                    <?php // $this->Html->link('Termo ' . $relatorio->termo_id, ['action' => 'sign', $relatorio->termo_id]); 
                                    ?>
                                </div>
                                <div class="d-inline-flex text-nowrap gap-1">
                                    <?php // $this->Html->link('<i class="bi bi-binoculars fs-6 me-2 text-white px-1"></i> Visualizar', ['action' => 'sign', $relatorio->id], ['class' => 'btn btn-sm btn-success d-flex align-items-center text-white py-0', 'target' => '_blank', 'escape' => false]); 
                                    ?>
                                </div>
                            </div>

                        <?php // } 
                        ?>
                    <?php // endforeach; 
                    ?>
                    <?php // if ($rel_final == 0) { 
                    ?>
                    <?php //'Sem relatórios';
                    // } 
                    ?>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card w-100">
                <div class="card-header d-flex justify-content-center bg-success text-white ">
                    <i class="bi bi-hourglass-top me-2"></i> TERMOS AGUARDANDO ASSINATURA
                </div>
                <div class="card-body d-grid gap-2">

                    <?php // $contador = $relatorios->where(['id_ass_dir is' => null])->count(); 
                    ?>
                    <?php // if ($contador != 0) {
                    //foreach ($relatorios as $relatorio) : 
                    ?>
                            <?php //if ($relatorio->id_ass_dir == '') { 
                            ?>
                                <div class="d-flex">
                                    <div class="w-100 text-truncate">
                                        <?php //if (!property_exists($relatorio, 'verdadeiro')) { 
                                        ?>
                                            <?php // $this->Html->link('Termo ' . $relatorio->termo_id, ['action' => 'edit', $relatorio->id]); 
                                            ?>
                                        <?php //  }  
                                        ?>
                                    </div>
                                    <div class="d-inline-flex text-nowrap gap-1">
                                        <?php // $this->Html->link('<i class="bi bi-pencil-square fs-6 me-2"></i> Visualizar', ['action' => 'sign', $relatorio->id], ['class' => $relatorio->ic_prioridade == 1 ? 'btn btn-sm btn-danger d-flex align-items-center me-1 py-0' : 'btn btn-sm btn-warning text-white d-flex align-items-center me-1 py-0', 'target' => '_blank',  'escape' => false]); 
                                        ?>
                                    </div>
                                </div>
                            <?php // } 
                            ?>
                        <?php //endforeach;
                        // } else //{ 
                        ?>
                        <?php //echo 'Sem relatórios'; 
                        ?>
                    <?php //} 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div> -->