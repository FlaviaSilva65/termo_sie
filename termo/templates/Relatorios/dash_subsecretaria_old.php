<div class="w-100 d-flex ">

    <div class="d-flex flex-wrap">
        <?php foreach ($supervisoras as $supervisora): ?>

            <div class="col-3 px-5 mb-5">
                <div class="d-flex align-items-center justify-content-center bg-purple-40 rounded-top-5 text-white py-2">
                    <h5 class="mb-0"><?= $supervisora->nm_usuario; ?></h5>
                </div>

                <?php foreach ($supervisora->usuario_unid_escolare->setore->unid_escolares as $escola) : ?>
                    <div class="col-12 bg-striped border-blue-15 my-2">
                        <p class="ms-2"> <?= $this->Html->link($escola->sigla . ' ' . $escola->nm_unid_escolar, ['action' => 'dash_sub_escolas', $escola->id,],['class' => 'text-color', 'escape' => false]); ?> </p>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

    </div>
</div>