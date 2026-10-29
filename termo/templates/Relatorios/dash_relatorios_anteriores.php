<div class="col-11 mx-auto d-flex my-5 justify-content-center align-items-stretch flex-wrap">
    <div class="col-11 col-lg-11  bg-white d-flex justify-content-evenly   flex-wrap">

    </div>

    <div class="col-12 mb-3">

        <div class="col-12 top-purple-bar mx-0 my-2 d-flex justify-content-center align-items-center">

            <p class="fs-3 text-white">RELATÓRIOS</p>

        </div>
        <div class=" p-0">
            <table class="w-100" style="border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr class="bg-light">
                        <th class="p-1 border border-card border-start-0 text-center">TERMO</th>
                        <th class="p-1 border border-card border-end-0 text-center">DATA</th>

                        <th class="p-1 border border-card border-end-0 text-center">DIR</th>
                        <th class="p-1 border border-card border-end-0 text-center"><i class="bi bi-eye fs-4 "></i></th>


                    </tr>
                </thead>

                <?php foreach ($relatorio_identificados as $relatorio) : ?>

                    <tr class="hv bg-striped border-blue-15 text-color">
                        <?php //debug($relatorio) ?>

                        <td class="text-center"><?= $relatorio->cd_termo . '/' . substr($relatorio->dt_relatorio, -4); ?></td>

                        <td class="text-center"><?= $relatorio->dt_relatorio ?></td>
                        <td class="text-center"><?php if ($relatorio->cd_assinatura_diretor) { ?>
                                <p><i class="bi bi-check fs-4 text-green-40"></i></p>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <?= $this->Html->link('<p><i class="bi bi-file-pdf fs-3 text-azul-40 "></i>',
                            ['controller' => 'Relatorios', 'action' => 'relatorioPdf', $relatorio->id],
                            ['escape' => false] ); ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </table>
        </div>
        <div class="bg-purple-40 rounded-bottom-5" style="height: 53px;">
        </div>
    </div>
</div>