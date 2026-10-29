<div class="row col-11 mx-auto px-3">

    <div class="bg-white shadow rounded border-secondary mt-4 pt-2">
        <?php

        foreach ($relatorios as $i => $relatorio) : ?>
            <?php
            $supervisor = $relatorio->supervisor['name'] ?? '-';
            if ($identity->isSupervisor) {
                if ($relatorio->supervisor_pode_editar) {

                    $classeSituacao = $relatorio->ic_rascunho == 1 ? 'alert-warning' : 'alert-success';
                    $textoSituacao = $relatorio->ic_rascunho == 1 ? 'Rascunho' : 'Assinado';
                    $cor =  'warning';
                    $acao = 'Editar';
                    $icone = 'pencil-square';

                    $urlAcao = [
                        'action' => 'manterPerguntas',
                        1,
                        $escola_id,
                        $relatorio->id
                    ];
                } else {
                    $classeSituacao = 'alert-success';
                    $textoSituacao = 'Assinado';
                    $cor = 'primary';
                    $acao = 'Visualizar';
                    $icone = 'eye-fill';

                    $urlAcao = [
                        'action' => 'visualizarPdf',
                        $relatorio->id
                    ];
                }
                // } elseif (($tipoUsuarioLogado == 1 && $relatorio->id_ass_dir == null) || ($tipoUsuarioLogado == 5 && $relatorio->id_ass_assis == null)) {
            } elseif (($identity?->isDiretor && $relatorio->id_ass_dir == null) || ($identity?->isAssistente && $relatorio->id_ass_assis == null)) {
                $classeSituacao = 'alert-danger';
                $textoSituacao = 'Pendente';

                $cor = 'danger';
                $acao = 'Assinar';
                $icone = 'pen-fill';
                $urlAcao = [
                    'action' => 'manterPerguntas',
                    1,
                    $escola_id,
                    $relatorio->id
                ];
            } elseif (($identity?->isDiretor && $relatorio->id_ass_dir != null) || ($identity?->isAssistente && $relatorio->id_ass_assis != null)) {
                // } elseif (($tipoUsuarioLogado == 1 && $relatorio->id_ass_dir != null) || ($tipoUsuarioLogado == 5 && $relatorio->id_ass_assis != null)) {
                $classeSituacao = 'alert-success';
                $cor = 'primary';
                $textoSituacao = 'Assinado';
                $acao = 'Visualizar';
                $icone = 'eye-fill';

                $urlAcao = [
                    'action' => 'visualizarPdf',
                    $relatorio->id
                ];
            } elseif ($identity?->isAdministrador && ($relatorio->id_ass_sub == null)) {
                // } elseif (($tipoUsuarioLogado == 4 || $tipoUsuarioLogado == 8) && $relatorio->id_ass_sub == null) {
                $classeSituacao = 'alert-danger';
                $textoSituacao = 'Pendente';
                $cor = 'danger';
                $acao = 'Assinar';
                $icone = 'pen-fill';

                $urlAcao = [
                    'action' => 'manterPerguntas',
                    1,
                    $escola_id,
                    $relatorio->id
                ];
            } elseif ($identity?->isAdministrador && ($relatorio->id_ass_sub != null)) {
                // } elseif (($tipoUsuarioLogado == 4 || $tipoUsuarioLogado == 8) && $relatorio->id_ass_sub != null) {
                $classeSituacao = 'alert-success';
                $cor = 'primary';
                $textoSituacao = 'Assinado';
                $acao = 'Visualizar';
                $icone = 'eye-fill';

                $urlAcao = [
                    'action' => 'visualizarPdf',
                    $relatorio->id
                ];
            }
            ?>

            <div class="row border-bottom border-light-secondary rounded px-2 pt-2 pb-1 g-0 hover">
                <div class="col-12 col-md-4 col-lg">
                    <p class="text-secondary lh-1 fs-7">N° Termo</p>
                    <p class="text-dark"><?= sprintf('%03d', $relatorio->termo_id) ?>/<?= $relatorio->data->format('Y') ?></p>
                </div>
                <div class="col-4 col-lg">
                    <p class="text-secondary lh-1 fs-7">Data da Visita</p>
                    <p class="text-dark"><?= $relatorio->data ?></p>
                </div>
                <div class="col">
                    <p class="text-secondary lh-1 fs-7">Supervisor(a)</p>
                    <p class="text-dark"><?= h($supervisor) ?></p>
                </div>

                <div class="col-12 d-flex col-lg-4">
                    <div class="col-1">
                        <p class="text-secondary lh-1 fs-7">Situação</p>
                        <div style="width:6rem; margin-top: 0.2rem" class="rounded-pill fs-7 text-center alert  <?= h($classeSituacao) ?>">
                            <?= h($textoSituacao) ?>
                        </div>
                    </div>
                    <div class="col text-end">
                        <p class="text-secondary lh-1 fs-7"> </p>

                        <?= $this->element('btn_pill', [
                            'label' => $acao,
                            'cor' => $cor,
                            'icon'  => $icone,
                            'url'   => $urlAcao,
                            'class' => 'btn-pill--fixed',
                        ]) ?>

                    </div>
                </div>

            </div>
        <?php
        endforeach;
        if ($relatorios->isEmpty()) { ?>
            <div class="text-center text-secondary">
                <h5> "Sem Termos, até o momento." </h5>
            </div>
        <?php } ?>
    </div>
    <div class="w-100 text-end mt-3">
        <?php $referer = $this->request->referer(true);

        if ($identity?->isAdministrador || $identity?->isSupervisor || $identity?->isDev) {
            $url = 'javascript:history.back()';
        } elseif (!$identity?->isSupervisor) {
            $url = ['action' => 'dash_escolas', !$relatorios->isEmpty() ?? $relatorios->place_id];
        } ?>
        <?php if (!$identity?->isGestor) { ?>
            <?= $this->element('btn_pill', [
                'label' => 'Voltar',
                'cor' => 'success',
                'icon'  => 'arrow-left',
                'url'   => $url,
                'class' => 'btn-pill--fixed',
            ]) ?>
        <?php } ?>
    </div>
</div>