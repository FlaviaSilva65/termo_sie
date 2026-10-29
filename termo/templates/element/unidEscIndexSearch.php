<div class="container mb-4">

    <div class="col-12 top-purple-bar mx-0 my-2 d-flex justify-content-center align-items-center">
        <div class="col-1">
            <h5 class="ms-4 text-white">Cód</h5>
        </div>
        <div class="col-4">
            <h5 class="ms-4 text-white">Escola</h5>
        </div>
        <div class="col-3">
            <h5 class="ms-4 text-white">E-mail</h5>
        </div>

        <div class="col-4 text-center">

        </div>
    </div>

    <?php foreach ($unidEscolares as $unidEscolar) : ?>
        <div class="col-12 bg-striped mx-0 my-2 d-flex justify-content-center align-items-center">
            <div class="col-1 ms-3">
                <?= $unidEscolar->id ?>
            </div>
            <div class="col-4">
                <?= $unidEscolar->sigla . ' ' . $unidEscolar->nm_unid_escolar ?>
            </div>
            <div class="col-3">
                <?= $unidEscolar->email ?>
            </div>

            <div class="col-4 text-end px-2">
                <?= $this->Html->link('<i class="bi bi-eye"></i>', ['action' => 'view', $unidEscolar->id], ['class' => 'btn btn-sm btn-info text-white px-4', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="bi bi-pencil-square"></i>', ['action' => 'edit', $unidEscolar->id], ['class' => 'btn btn-sm btn-primary text-white px-4', 'escape' => false]) ?>
                <?= $this->Form->postLink('<i class="bi bi-trash3"></i>', ['action' => 'delete', $unidEscolar->id], ['confirm' => 'Tem certeza?', 'class' => 'btn btn-sm btn-danger text-white px-4', 'escape' => false]) ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col d-flex justify-content-center mt-3 text-white">
        <div class="paginator mt-5">
            <ul class="pagination">


                <?= $this->Paginator->prev('« Anterior') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('Próxima »') ?>
            </ul>
        </div>
    </div>

</div>