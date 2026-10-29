<style>
    .pads {
        padding-left: 2.3rem;
    }
</style>

<div class="w-100 d-flex bg-white p-3 pb-2 rounded-top shadow">
    <i class="bi bi-journal-text fs-2 bg-primary text-white rounded me-2 pt-1 px-1 lh-1" style="height:1.3em;"></i>

    <div>
        <p class="mb-0 fs-6 fw-bold text-primary">
            DIMENSÃO <?= $romanos[$dimensao] ?> -
            <?= mb_strtoupper($dimensoes[$dimensao - 1]->titCompleto) ?>
        </p>

        <p class="mb-0 fs-7 text-secondary">
            <?= $dimensoes[$dimensao - 1]->pergTotal ?> Perguntas
        </p>
    </div>
</div>

<?= $this->Form->create($relatorio, [
    "id" => "form-perguntas",
    "autocomplete" => "off",
    "novalidate",
    "url" => array_merge(
        ['action' => 'manterPerguntas', $dimensao, $escola_id, $relatorio->id],
        $somentePendencias ? ['?' => ['pendencias' => 1]] : []
    ),
]) ?>

<?php
$podeResolverPendencias = !empty($somentePendencias)
    && !empty($identity->isSupervisor);
?>

<fieldset <?= !empty($modoSomenteLeitura) && !$podeResolverPendencias ? 'disabled' : '' ?>>

    <?php
    $bloquearRespostas = !empty($modoSomenteLeitura);
    ?>

    <?php foreach ($perguntas as $key => $p): ?>

        <?php
        $respostaSalva = $respostasSalvas[$p->id] ?? null;
        $providencia = $providenciasSalvas[$p->id] ?? null;
        ?>
        <?php
        $respondida = false;
        $dataResposta = null;

        if ($respostaSalva) {

            if ($p->tipo === 'radio') {

                // Considera inclusive a resposta "0" como preenchida
                $respondida = $respostaSalva->resposta !== null
                    && trim((string)$respostaSalva->resposta) !== '';
            } elseif ($p->tipo === 'checkbox') {

                // Verifica se existe alguma ocorrência selecionada
                foreach ($ocorrencias[$p->id] ?? [] as $ocorrencia) {
                    if (in_array($ocorrencia->id, $ocorrenciaIdsSalvas)) {
                        $respondida = true;
                        break;
                    }
                }

                // Também considera a observação preenchida
                if (!empty(trim((string)($respostaSalva->observacao ?? '')))) {
                    $respondida = true;
                }
            } elseif ($p->tipo === 'texto') {

                $respondida = trim((string)($respostaSalva->observacao ?? '')) !== '';
            }
        }

        if ($p->tipo === 'data') {
            $respondida = !empty($relatorio->data);
        }

        if ($respondida) {
            $dataResposta = $p->tipo === 'data'
                ? ($relatorio->modified ?? null)
                : ($respostaSalva->modified ?? null);
        }
        ?>
        <div class="d-flex xbg-white shadow border-5 border-start importanciaCor<?= substr($p->importancia, 0, 1) ?>">
            <div class="w-100">
                <div class="d-flex align-items-center my-1">
                    <div
                        class="rounded-circle bg-primary ms-2 me-2 d-flex flex-shrink-0 align-items-center justify-content-center"
                        style="width:1.3rem;height:1.3rem;">
                        <h6 class="mb-0 text-white">
                            <?= $key + 1 ?>
                        </h6>
                    </div>
                    <p class="text-dark small fs-6">
                        <?= $p->descricao ?>
                    </p>
                </div>
                <?php if ($p->tipo == "checkbox"): ?>

                    <!-- Checkbox -->
                    <div class="row g-1 pads">
                        <?php foreach ($ocorrencias[$p->id] as $key => $ocorrencia): ?>
                            <?php
                            $marcado = in_array(
                                $ocorrencia->id,
                                $ocorrenciaIdsSalvas
                            );
                            ?>
                            <div class="col-4 col-lg-3 d-flex align-items-stretch">
                                <?= $this->Form->control(
                                    "respostas.{$p->id}.ocorrencias.{$ocorrencia->id}",
                                    [
                                        'type' => 'checkbox',
                                        'checked' => $marcado,
                                        'disabled' => $bloquearRespostas,
                                        'label' => [
                                            'text' => '<span>' . $ocorrencia->nm_tp_ocorrencia . '</span>',
                                            'class' => 'checkbox-resposta mt-0',
                                            'escape' => false,
                                        ],
                                    ]
                                ) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($p->tipo == "radio"): ?>
                    <!-- Radio button -->
                    <div class="d-flex flex-wrap align-items-center gap-3 pads">

                        <div class="grupo-resposta">
                            <?= $this->Form->control(
                                "respostas.{$p->id}.resposta",
                                [
                                    'type' => 'radio',
                                    'options' => json_decode($p->opcoes ?? '', true),
                                    'value' => $respostaSalva->resposta ?? null,
                                    'label' => false,
                                    'legend' => false,
                                    'class' => 'form-check-input mt-0' . ($p->id == 35 ? ' resposta-35' : ''),
                                    'disabled' => $bloquearRespostas,
                                    'templates' => [
                                        'radioWrapper' => '<div class="resposta-radio mb-2">{{label}}</div>',
                                        'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
                                        'radioLabel' => '<label{{attrs}}>{{input}}{{text}}</label>',
                                    ],
                                ]
                            ) ?>
                        </div>

                        <?php if ($p->id == 35): ?>
                            <input type="hidden" name="responsavel_id" class="id-responsavel" value="">
                            <div class="usuario-select mb-2">
                                <div class="usuario-select-wrapper">
                                    <div class="usuario-select-display">
                                        <span class="usuario-selecionado">
                                            ⇩ Selecione quem acompanhou
                                        </span>
                                    </div>
                                    <div class="usuario-select-options p-1">
                                        <?php foreach ($relatorio->gestores as $value => $nome): ?>
                                            <div class="usuario-option px-1 rounded text-secondary" data-value="<?= h($value) ?>">
                                                <i class="bi bi-person-circle pt-3"></i>
                                                <span class="usuario-nome text-nowrap"><?= h($nome) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?= $this->Form->error('responsavel_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($p->tipo == "data"): ?>

                    <div class="pads">
                        <?= $this->Form->control(
                            'relatorio.data',
                            [
                                'type' => 'date',
                                'max' => date('Y-m-d'),
                                'label' => false,
                                'class' => 'mb-2 py-1 text-secondary',
                                'style' => 'width:10rem;',
                                'value' => $relatorio->data ?? null,
                                'disabled' => $bloquearRespostas,
                            ]
                        ) ?>
                    </div>
                <?php endif; ?>

                <?php if ($p->id != 35 && $p->tipo != "data") : ?>
                    <div class="pads">
                        <?= $this->Form->control(
                            "respostas.{$p->id}.observacao",
                            [
                                "type" => "textarea",
                                "rows" => 1,
                                "label" => false,
                                "class" => "mt-2 smart-textarea place-cor",
                                "placeholder" => "+ Adicionar observação",
                                "value" => $respostaSalva->observacao ?? '',
                                "disabled" => $bloquearRespostas,
                            ]
                        ) ?>
                        <div
                            class="invalid-feedback"
                            id="erro-observacao-<?= $p->id ?>">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Informe a observação para registrar o acompanhamento como pendência.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="border-start ms-2 px-2 d-flex flex-wrap align-items-center d-print-none">
                <div style="width:136px;">
                    <div class="d-flex justify-content-center align-items-center mb-2" data-resposta-data="<?= (int)$p->id ?>">
                        <?php if ($respondida): ?>
                            <i class="bi bi-check-circle me-2 fs-6 text-success"></i>
                            <div>
                                <p class="d-flex mb-0 fs-7 text-success lh-1">
                                    Respondida
                                </p>
                                <?php if ($dataResposta): ?>
                                    <p class="text-nowrap mb-0 fs-8 text-secondary">
                                        em <?= h($dataResposta->format('d/m/Y H:i')) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>

                            <i class="bi bi-clock me-2 fs-6 text-warning"></i>

                            <div>
                                <p class="mb-0 fs-7 text-warning">
                                    Não respondida
                                </p>
                            </div>

                        <?php endif; ?>
                    </div>

                    <?php
                    $statusSalvo = $respostaSalva->status ?? 1;

                    $pendenciaAberta = $providencia
                        && (int)$providencia->status === 0;

                    $statusSalvo = $pendenciaAberta ? 0 : 1;

                    $cor = $pendenciaAberta ? 'warning' : 'success';

                    $icone = $pendenciaAberta
                        ? 'check-circle'
                        : 'exclamation-circle';

                    $texto = $pendenciaAberta
                        ? (!empty($somentePendencias) ? 'Resolver' : 'Em')
                        : 'Requerer';
                    ?>
                    <?php if ($p->tipo != "data" && $p->id != 35): ?>

                        <?php
                        $resolvida = $providencia
                            && (int)$providencia->status === 1
                            && !empty($providencia->resolvido_em);

                        $pendenciaAberta = $providencia
                            && (int)$providencia->status === 0;

                        $cor = $pendenciaAberta ? 'warning' : 'success';
                        $icone = $pendenciaAberta ? 'check-circle' : 'exclamation-circle';
                        $texto = $pendenciaAberta
                            ? (!empty($somentePendencias) ? 'Resolver' : 'Em')
                            : 'Requerer';

                        $permiteResolver = !empty($somentePendencias);
                        ?>

                        <?php if ($resolvida): ?>

                            <!-- Pendência resolvida -->
                            <div class="d-flex justify-content-center align-items-center">

                                <i class="bi bi-check-circle text-success me-2 fs-6"></i>

                                <div>
                                    <p class="mb-0 fs-7 text-success lh-1">
                                        Resolvido
                                    </p>

                                    <p class="text-nowrap mb-0 fs-8 text-secondary">
                                        em <?= h($providencia->resolvido_em->format('d/m/Y H:i')) ?>
                                    </p>
                                </div>

                            </div>

                        <?php else: ?>

                            <!-- Botão de acompanhamento -->
                            <?php if ($p->id != 34 && (empty($somentePendencias) || $pendenciaAberta)) : ?>
                                <button
                                    type="button"
                                    class="btn-acompanhamento btn btn-<?= $cor ?> btn-sm btn-pill btn-pill--start btn-pill--block"
                                    data-pergunta-id="<?= (int)$p->id ?>">

                                    <span class="btn-pill__icon fs-6 text-white">
                                        <i class="bi bi-<?= $icone ?>"></i>
                                    </span>

                                    <span class="btn-pill__label btn-pill__label--multi fs-8">
                                        <span class="js-acomp-texto"><?= $texto ?></span>
                                        <span>Acompanhamento</span>
                                    </span>

                                </button>
                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>

            </div>

        </div>
        <?php if ($p->id != 35 && $p->tipo != 'data'): ?>

            <?= $this->Form->hidden(
                "respostas.{$p->id}.status",
                ['value' => $statusSalvo]
            ) ?>
        <?php endif; ?>

    <?php endforeach; ?>

</fieldset>

<?= $this->Form->end() ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const radios35 = document.querySelectorAll('.resposta-35');

        const usuarioSelect = document.querySelector('.usuario-select');

        if (!usuarioSelect) {
            return;
        }

        // Em modo somente leitura, trava a interação com o seletor customizado
        if (typeof modoSomenteLeitura !== 'undefined' && modoSomenteLeitura) {
            usuarioSelect.classList.add('pe-none');
            usuarioSelect.style.opacity = '0.7';
        }

        const hiddenUsuario = usuarioSelect
            .parentElement
            .querySelector('.id-responsavel');

        const selecionado = usuarioSelect.querySelector(
            '.usuario-selecionado'
        );

        const display = usuarioSelect.querySelector(
            '.usuario-select-display'
        );

        const optionsContainer = usuarioSelect.querySelector(
            '.usuario-select-options'
        );

        const options = usuarioSelect.querySelectorAll(
            '.usuario-option'
        );


        function atualizarUsuarioSelect() {

            const radioSelecionado = document.querySelector(
                '.resposta-35:checked'
            );


            // Se marcou SIM
            if (
                radioSelecionado &&
                radioSelecionado.value === '1'
            ) {

                usuarioSelect.classList.remove('d-none');


                // Se existe somente um usuário
                if (options.length === 1) {

                    const option = options[0];

                    selecionado.innerHTML = option.innerHTML;

                    hiddenUsuario.value = option.dataset.value;

                    usuarioSelect.classList.add('read');

                } else {

                    usuarioSelect.classList.remove('read');

                }

            } else {

                // NÃO ou nenhuma resposta

                usuarioSelect.classList.add('d-none');


                // Limpa o valor
                hiddenUsuario.value = '';


                // Limpa o texto
                selecionado.innerHTML =
                    '⇩ Selecione quem acompanhou';


                // Fecha as opções
                usuarioSelect.classList.remove('aberto');

            }

        }


        // Clique nas opções de usuários
        options.forEach(option => {

            option.addEventListener('click', function() {

                selecionado.innerHTML = option.innerHTML;

                hiddenUsuario.value = option.dataset.value;

                usuarioSelect.classList.remove('aberto');

            });

        });


        // Clique para abrir o select
        display.addEventListener('click', function() {

            // Se só existe um usuário, não abre
            if (usuarioSelect.classList.contains('read')) {
                return;
            }


            // Só permite abrir se existir mais de um
            if (options.length > 1) {

                usuarioSelect.classList.toggle('aberto');

            }

        });


        // Fecha ao sair do campo
        optionsContainer.addEventListener('mouseleave', function() {

            usuarioSelect.classList.remove('aberto');

        });


        // Evento dos radios
        radios35.forEach(radio => {

            radio.addEventListener(
                'change',
                atualizarUsuarioSelect
            );

        });


        // Estado inicial
        atualizarUsuarioSelect();

    });
</script>