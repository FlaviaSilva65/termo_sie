<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

?>
<!DOCTYPE html>
<html data-bs-theme="blueGreen25">

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Termos</title>

    <?= $this->Html->meta('favicon.png', '/favicon.png', ['type' => 'icon']) ?>

    <?= $this->Html->css([
        'bootstrap.min',
        'bootstrap_icon_min',
        'page_home',
        'header',
        'footer',
        // 'style',
        'print',
        'main',
        'smartTextarea',

    ]) ?>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> -->

    <?=
    $this->Html->script([
        'jquery.min',
        'bootstrap.bundle.min',
        'js47061',
        'script',
        'smartTextarea',
    ])
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<!-- <body class="d-flex flex-column min-vh-100" data-bs-spy="scroll" data-bs-root-margin="-40% 0% -40% 0%" data-bs-smooth-scroll="true"> -->

<body class="d-flex flex-column min-vh-100">
    <?= $this->element('header') ?>

    <main class="container-fluid flex-grow-1 px-0 mt-4">
        <div class="text-center">
            <?= $this->Flash->render(); ?>
        </div>
        <?php
        $session = $this->request->getSession();

        if ($session->check('Impersonate.usuario_id')):
        ?>

            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <div>
                    <strong>Modo Desenvolvedor</strong>
                    Você está utilizando o sistema como
                    <strong><?= h($this->request->getAttribute('identity')->nm_usuario) ?></strong>
                </div>

                <?= $this->Html->link(
                    'Voltar para Admin',
                    [
                        'controller' => 'Impersonacao',
                        'action' => 'pararImpersonacao'
                    ],
                    ['class' => 'btn btn-sm btn-dark']
                ) ?>
            </div>
        <?php endif; ?>

        <?= $this->fetch('content') ?>
    </main>

        <?php if (isset($identity)) { ?>

            <!-- <div class="container vstack px-0">
                <div class="position-relative px-4 
                <?php ($this->request->getParam('action') == 'add') ||
                    ($this->request->getParam('controller') == 'Providencias') ||
                    $this->request->getParam('controller') == ('Usuarios' || 'UnidEscolares') ? 'wrapper' : ''  ?>">
                    
                </div>
            </div> -->

        <?php } ?>
        
    <?= $this->element('footer') ?>
</body>

</html>