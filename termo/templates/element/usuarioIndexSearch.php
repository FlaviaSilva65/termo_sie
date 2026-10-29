<div class="container mb-4">

    <table class="table table-striped border rounded overflow-hidden align-middle">
        <thead>
            <tr>
                <th scope="col">
                    <span class="text-uppercase text-nowrap">Nome</span>
                </th>
                <th scope="col">
                    <span class="text-uppercase text-nowrap">CPF</span>
                </th>
                <th scope="col">
                    <span class="text-uppercase text-nowrap">E-mail</span>
                </th>
                <th scope="col">
                    <span class="text-uppercase text-nowrap">Ativo</span>
                </th>
                <th scope="col">
                    <span class="text-uppercase text-nowrap">Grupo</span>
                </th>
                <th scope="col" class="text-center">
                    <span class="text-uppercase text-nowrap">Ações</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $user) : ?>
                <tr>
                    <td scope="row"><?= h(trim($user->nm_usuario)) ?></td>
                    <td scope="row"><?= $user->cd_cpf ?></td>
                    <td scope="row"><?= $user->email ?></td>
                    <td scope="row"><?= ($user->ic_ativo == '1' ? '<i class="bi bi-check text-success fs-2"></i>' : 'Inativo') ?></td>
                    <td scope="row"><?= $user->tp_usuario->nm_tp_usuarios ?></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <?= $this->Html->link(
                                '<i class="bi bi-eye"></i>',
                                ['action' => 'view', $user->id],
                                [
                                    'class' => 'btn btn-primary btn-sm rounded-pill px-2 py-1 btn-acao',
                                    'escape' => false,
                                    'title' => 'Visualizar'
                                ]
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="bi bi-pencil-square"></i>',
                                ['action' => 'edit', $user->id],
                                [
                                    'class' => 'btn btn-success btn-sm rounded-pill px-2 py-1 btn-acao',
                                    'escape' => false,
                                    'title' => 'Visualizar'
                                ]
                            ) ?>
                            <?php $this->Form->postLink(
                                '<i class="bi bi-trash3"></i>',
                                ['action' => 'delete', $user->id],
                                [
                                    'class' => 'btn btn-danger btn-sm rounded-pill px-2 py-1 btn-acao',
                                    'escape' => false,
                                    'title' => 'Visualizar'
                                ]
                            ) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

    <?php
    $this->Paginator->setTemplates([
        'sort' => '<span class="text-uppercase text-nowrap">{{text}}</span><a href="{{url}}" class="sort-link d-flex text-muted ps-1 no-print"><i class="bi bi-arrow-down-up" title="Ordenar"></i></a>',
        'sortAsc' => '<span class="text-uppercase text-nowrap">{{text}}</span><a href="{{url}}" class="sort-link d-flex text-muted ps-1 no-print"><i class="bi bi-sort-up" title="Crescente"></i></a>',
        'sortDesc' => '<span class="text-uppercase text-nowrap">{{text}}</span><a href="{{url}}" class="sort-link d-flex text-muted ps-1 no-print"><i class="bi bi-sort-down" title="Decrescente"></i></a>',
        'first' => '<li class="page-item"><a class="page page-link" href="{{url}}" data-page="first" title="Primeira"><i class="bi bi-chevron-double-left"></i></a></li>',
        'prevActive' => '<li class="page-item"><a class="page page-link" href="{{url}}" data-page="previous" title="Anterior"><i class="bi bi-chevron-left"></i></a></li>',
        'prevDisabled' => '<li class="page-item disabled"><a class="page page-link" href="{{url}}" data-page="previous" title="Anterior"><i class="bi bi-chevron-left"></i></a></li>',
        'nextActive' => '<li class="page-item"><a class="page page-link" href="{{url}}" data-page="next" title="Próxima"><i class="bi bi-chevron-right"></i></a></li>',
        'nextDisabled' => '<li class="page-item disabled"><a class="page page-link" href="{{url}}" data-page="next" title="Próxima"><i class="bi bi-chevron-right"></i></a></li>',
        'last' => '<li class="page-item"><a class="page page-link" href="{{url}}" data-page="last" title="Última"><i class="bi bi-chevron-double-right"></i></a></li>',
        'number' => '<li class="page-item"><a class="page page-link page-number" data-page="1" href="{{url}}">{{text}}</a></li>',
        'current' => '<li class="page-item active"><a class="page page-link page-number" data-page="{{text}}">{{text}}</a></li>',
        'counterPages' => '<div class="text-uppercase"><span class="no-print"><span>Página</span> <b class="page">{{page}}</b> <span>de</span> <b class="pages">{{pages}}</b><span>, </span></span><span>Exibindo</span> <b class="rows">{{current}}</b> <span>de</span> <b class="count">{{count}}</b> <span>registros</span></div>'

    ]);
    ?>

    <?= $this->Paginator->counter(); ?>
    <nav>
        <ul class="pagination">
            <?= $this->Paginator->first() ?>
            <?= $this->Paginator->prev() ?>

            <?= $this->Paginator->numbers() ?>

            <?= $this->Paginator->next() ?>

            <?= $this->Paginator->last() ?>

        </ul>

    </nav>
</div>
<style>
    .btn-acao {
        width: 38px;
        height: 26px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
</style>