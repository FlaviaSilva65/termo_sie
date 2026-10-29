<?php
// Essa variável será excluída 
$modelo = 1;

$acaoAtual = $this->request->getParam('action');

$urlInicio = [
    'plugin' => null,
    'controller' => 'Relatorios'
];

if (
    $this->request->getParam('action') == 'dashEscolas'
    || ($this->request->getParam('action') == 'providencia')
) {
    $urlPend = [
        'plugin' => null,
        'controller' => 'Relatorios',
        'action' => 'dashEscolas',
        $identity->places[$escola_id],
        '?' => [
            'pendentes' => 1
        ]
    ];
}

if ($identity?->isSupervisor) {

    $urlInicio['action'] = 'dashSupervisor';
    // $urlInicio[] = $identity?->id;
} elseif ($identity?->isAdministrador) {
    $urlInicio['action'] = 'dashSubsecretaria';
} elseif ($identity?->isGestor) {
    $urlInicio['action'] = 'dashEscolas';
    $urlInicio[] = isset($escola) ? $escola_id : ($escola_id ?? '');
} elseif ($identity?->isDev) {
    $urlInicio = [
        'controller' => 'Usuarios',
        'action' => 'index'
    ];
} else {
    $urlInicio = [
        'controller' => 'Pages',
        'action' => 'index'
    ];
}
?>

<!-- Calculando os relatorios em assinatura -->

<div class="w-100 bg-primary text-white px-3">
    <div class="col-11 mx-auto d-flex justify-content-between">
        <div class="d-flex align-items-center">

            <?php if ($identity?->isSupervisor && $this->request->getParam('action') === 'dashSupervisor'): ?>
                <i class="bi bi-buildings fs-4 me-2"></i>
                <h6 class="mb-0 me-4">Escolas</h6>

                <h6 class="info-rounded-pill align-self-center bg-dark-quaternary small mb-0 me-4 d-flex align-items-center">
                    <span class="info-s-pill me-1"><i class="bi bi-building"></i></span>Total de Escolas: <?= count($titulos) ?>
                </h6>
                <h6 class="info-rounded-pill align-self-center bg-danger small mb-0 d-flex align-items-center">
                    <span class="info-s-pill me-1"><i class="bi bi-pencil-square"></i></span>Ações Pendentes: <?= $totalPendencias ?>
                </h6>

            <?php endif; ?>

            <?php if (($acaoAtual === 'manterPerguntas'  && isset($relatorio)) || $acaoAtual === 'dashEscolas' || $acaoAtual === 'pendencias'): ?>
                <?php
                $termo = $relatorio->termo_id ?? null;
                // Situação
                $emPreenchimento = isset($relatorio) ? $relatorio->ic_rascunho == 1 : false;

                // Escola
                if (isset($escolaName)) {
                    $nomeEscola = $escolaName->sigla . ' ' . $escolaName->nm_unid_escolar;
                } elseif (isset($escola_id)) {
                    $nomeEscola = $identity->places[$escola_id];
                } else {
                    $nomeEscola = '';
                }
                ?>

                <!-- ESCOLA -->
                <?php if ($nomeEscola): ?>
                    <?= $this->Html->link('<div class="text-white d-flex align-items-center me-4">
                                            <i class="bi bi-buildings me-2 fs-4"></i>
                                            <h6 class="mb-0">' .
                        h($nomeEscola) .
                        '</h6>
                                            </div>', [
                        'action' => 'dash_escolas',
                        isset($escola) ? $escola_id : $escola_id
                    ], ['escape' => false]) ?>


                <?php endif; ?>

                <!-- TERMO -->
                <?php if ($termo): ?>
                    <div class="d-flex align-items-center me-4">
                        <i class="bi bi-journal-text fs-4 me-2"></i>

                        <h6 class="mb-0">
                            Termo de Supervisão - Nº <?= sprintf('%03d', $termo) ?>
                        </h6>
                    </div>
                <?php endif; ?>

                <?php if ($this->request->getParam('action') === 'dashEscolas'): ?>
                    <?php
                    $urlNovoRelatorio = [
                        'plugin' => null,
                        'controller' => 'Relatorios',
                        'action' => 'manterPerguntas',
                        1,
                        $escola_id,
                        '?' => ['novo' => 1],
                    ];
                    ?>
                    <?php if ($identity?->isSupervisor) { ?>
                        <!-- NOVO TERMO / RELATÓRIO -->
                        <?= $this->Html->link(
                            '<i class="bi bi-file-earmark-plus fs-4 me-2"></i>
                        <span class="fs-6">Novo Termo</span>',
                            $urlNovoRelatorio,
                            [
                                'class' => 'd-flex align-items-center py-0 me-3 px-1 btn btn-primary btn-sm',
                                'escape' => false
                            ]
                        ) ?>
                    <?php } ?>

                <?php endif; ?>
                <!-- PENDÊNCIAS (só na tela dashEscolas, logo após o nome da escola) -->
                <?php if ($this->request->getParam('action') === 'dashEscolas' && isset($escola)): ?>

                    <!-- Colocar uma URL para filtrar somente os termos Pendentes de Assinatura -->
                    <?php $this->Html->link(
                        '<h6 class="info-rounded-pill align-self-center bg-dark-warning mb-0 d-flex align-items-center me-3">
                        <span class="info-s-pill me-1">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        Sem assinatura: ' . (isset($relatoriosPendentes) ? $relatoriosPendentes : 0) . '</h6>',
                        $urlPend,
                        [
                            'class' => 'd-flex align-items-center py-0 px-1 btn btn-primary btn-sm',
                            'escape' => false,
                            'title' => 'Termos pendentes de assinatura'
                        ]
                    ) ?>
                <?php endif; ?>

                <!-- Inconformidades (Aguardando Providências -->
                <?php if ($acaoAtual === 'dashEscolas' && isset($pendencias) || $acaoAtual === 'pendencias'): ?>

                    <?php
                    isset($pendencias) ? $pendencias = count($pendencias) : $pendencias = array_sum(array_map('count', $pendenciasPorRelatorio));

                    $urlPendencias = [
                        'plugin' => null,
                        'controller' => 'Relatorios',
                        'action' => 'pendencias',
                        $escola_id
                    ];
                    ?>
                    <?php if ($acaoAtual === 'pendencias'): ?>
                        <h6 class="info-rounded-pill align-self-center bg-success mb-0 d-flex align-items-center me-3">
                            <span class="info-s-pill me-1">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                            Total de Termos: <?= count($pendenciasPorRelatorio) ?>
                        </h6>

                    <?php endif; ?>

                    <?php
                    $htmlPendencias = '<h6 class="info-rounded-pill align-self-center bg-danger mb-0 d-flex align-items-center" style="letter-spacing: 0.10rem;">
                        <span class="info-s-pill me-1">
                            <i class="bi bi-check-square"></i>
                        </span>
                        Ações Pendentes: ' . $pendencias . '</h6>';
                    ?>

                    <?php if ($acaoAtual === 'pendencias'): ?>
                        <!-- Já está na tela de pendências: não clicável -->
                        <h6 class="info-rounded-pill align-self-center bg-danger mb-0 d-flex align-items-center me-3">
                            <span class="info-s-pill me-1">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                            Ações Pendentes: <?= $pendencias ?>
                        </h6>

                    <?php else: ?>

                        <?= $this->Html->link(
                            '<h6 class="info-rounded-pill align-self-center bg-danger mb-0 d-flex align-items-center" style="letter-spacing: 0.10rem;">
                        <span class="info-s-pill me-1">
                            <i class="bi bi-check-square"></i>
                        </span>
                        Ações Pendentes: ' . $pendencias . '</h6>',
                            $urlPendencias,
                            [
                                'class' => 'd-flex align-items-center py-0 px-1 btn btn-primary btn-sm',
                                'escape' => false,
                                'title' => 'Termos com pendências apontadas'
                            ]
                        ) ?>

                    <?php endif; ?>
                <?php endif; ?>

                <!-- SITUAÇÃO -->
                <?php if (isset($relatorio)): ?>
                    <h6 class="info-rounded-pill align-self-center
                        <?= $emPreenchimento ? 'bg-warning text-dark' : 'bg-dark-quaternary' ?>
                        small mb-0 d-flex align-items-center">

                        <?php if ($emPreenchimento): ?>

                            <span class="info-s-pill me-1">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                            Em preenchimento
                        <?php else: ?>
                            <span class="info-s-pill me-1">
                                <i class="bi bi-check-circle"></i>
                            </span>
                            Finalizado
                        <?php endif; ?>
                    </h6>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (
                isset($identity) &&
                ($identity?->isDev)
            ): ?>
                <?php
                $urlUsuarios = [
                    'controller' => 'Usuarios',
                    'action' => 'add'
                ];
                ?>

            <?php endif; ?>
        </div>

        <?php if (!empty($identity)) {  ?>
            <div class="d-flex align-items-center ms-auto">
                <?php
                if (isset($identity)) {
                    echo
                    $this->Html->link(
                        '<i class="bi bi-house fs-4 me-2"></i><span class="fs-6">Início</span>',
                        $urlInicio,
                        ['class' => 'd-flex align-items-center py-0 me-4 px-1 btn btn-primary btn-sm', 'escape' => false]
                    );
                } ?>
            </div>
            <div class="dropdown my-auto">
                <button class="nav-link d-flex align-items-center border-0 bg-transparent"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <?php
                    // if (isset($identity)) {
                    //     dd($identity);
                    // }
                    ?>

                    <i class="bi bi-person-circle fs-4 text-white me-2"></i>
                    <div class="text-start">
                        <?php if ($identity->employees) { ?>
                            <p class="fs-7 lh-1 mb-0 text-white">
                                <?= h($identity?->cargo_reduzido) ?>
                            </p>
                            <h6 class="small mb-0 lh-1 fs-6 text-white" title="<?= h($identity?->nome_reduzido) ?>">
                                <?= h($identity?->nome) ?>
                            </h6>
                        <?php } ?>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li class="dropdown-item-text">
                        <?= $this->Html->link(
                            '<i class="bi bi-door-open"></i><span>Sair</span>',
                            [
                                'plugin' => null,
                                'controller' => 'Usuarios',
                                'action' => 'logout'
                            ],
                            [
                                'class' => 'text-dark-primary',
                                'escape' => false
                            ]
                        ) ?>
                    </li>
                </ul>
            </div>


        <?php } ?>

    </div>
</div>