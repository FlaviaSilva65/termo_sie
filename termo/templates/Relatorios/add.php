<?= $this->Form->create($relatorio, ['id' => 'formRelatorio']) ?>

<?php // $date = ($relatorio->id ? $relatorio->data->format('Y-m-d') : date('Y-m-d')); 
?>

<?= $this->Form->hidden('usuario_id', ['value' => $identity->id]) ?>
<?= $this->Form->hidden('unid_escolar_id', ['value' => $escola->id]) ?>


<div class="col-12 col-md-12 mx-auto">

    <!-- ================== QUESTÃO 1 ================== -->
    <div class="bg-success py-2 my-2 rounded">
        <h5 class="m-0 text-center text-white letter-space ">ACOMPANHAMENTO GERAL - TERMO nº <?= $relatorio->termo_id ?> </h5>
    </div>

    <?php $ocorrenciaIndex = 0; ?>

    <?php foreach ($perguntas as $i => $pergunta): ?>
        <div class="col-12 px-0">
            <div class="<?= $pergunta->codigo == 34 ? 'bg-striped-yellow' : 'bg-blue-40' ?> text-question py-2 rounded">
                <h6 class="mb-0 ps-3 fw-bold">
                    <?= $pergunta->codigo  . '. ' . $pergunta->descricao ?>
                    <!-- 1. Houve acompanhamento da equipe gestora na entrada e/ou saída dos alunos? -->
                </h6>
            </div>

            <div class="mx-0 px-0">
                <?= $this->Form->hidden('respostas.' . $i . '.id', [
                    'type' => 'hidden'
                ]); ?>
                <?= $this->Form->control('respostas.' . $i . '.pergunta_id', [
                    'type' => 'hidden',
                    // 'value' => $pergunta->id
                ]); ?>

                <div class="col-12 px-0 mx-0 mt-2">

                    <?php
                    // echo $this->Form->hidden('respostas.' . $i . '.id');

                    if ($pergunta->tipo == 'radio') {
                        echo  $this->Form->control('respostas.' . $i . '.resposta', [
                            'type' => 'radio',
                            'options' => is_array($pergunta->opcoes)
                                ? $pergunta->opcoes
                                : json_decode($pergunta->opcoes, true),
                            'label' => false,
                            'legend' => false,
                            'class' => 'me-2 form-check-input mt-0 rounded-circle',
                            'templates' => [
                                'radioWrapper' => '<div class="d-inline me-3">{{label}}</div>',
                                'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class_="form-check-input  me-2" style="margin-top:0.1em;">',
                            ]
                        ]);

                        // echo $this->Form->hidden('respostas.' . $i . '.id');

                        // echo $this->Form->hidden('respostas.' . $i . '.pergunta_id');
                    } elseif ($pergunta->tipo == 'checkbox') { ?>
                        <div class="row">
                            <?php if ($pergunta->id == 16) { ?>

                                <?php foreach ($list_ocorrencia as $h => $ocorrencia) : ?>
                                    <?php if ($ocorrencia->categorias_id == 3) { ?>
                                        <div class="col-6 col-md-3 mb-2">
                                            <div class="bg-striped p-2 d-flex align-items-center rounded">
                                                <?php
                                                echo $this->Form->control('ocorrencia_relatorios.' . $ocorrenciaIndex . '.id', [
                                                    'value' => $ocorrenciasExistentes[$ocorrencia->id]['id'] ?? '',
                                                    'type' => 'hidden'
                                                ]);
                                                echo $this->Form->control(
                                                    // 'respostas.' . $i . '.resposta',
                                                    'ocorrencia_relatorios.' . $ocorrenciaIndex . '.ocorrencia_id',
                                                    [
                                                        'value' => $ocorrencia->id,
                                                        'checked' => in_array($ocorrencia->id, $ocorrenciasMarcadas ?? []),
                                                        'hiddenField' => false,
                                                        'type' => 'checkbox',
                                                        'class' => 'mx-2 form-check-input mt-0 rounded',
                                                        'id' => 'formGroupManut_' . $ocorrenciaIndex,
                                                        'label' => $ocorrencia->nm_tp_ocorrencia,
                                                        'templates' => [
                                                            'inputContainer' => '{{content}}',
                                                            'label' => '<label class="d-flex align-items-center m-0">{{input}} <span class="ms-2 mb-1">{{text}}</span></label>',
                                                            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} style="height: 20px; width: 20px; ">',
                                                            'errorClass' => 'mx-2 form-check-input mt-0 rounded',
                                                        ]
                                                    ]
                                                );

                                                $ocorrenciaIndex++;

                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php endforeach; ?>
                        </div>
                        <div class="row">
                        <?php } elseif ($pergunta->id == 18) { ?>

                            <?php foreach ($list_ocorrencia as $ocorrencia) : ?>
                                <?php if ($ocorrencia->categorias_id == 4) { ?>
                                    <div class="col-12 col-md-4 mb-2">
                                        <div class="bg-striped p-2 d-flex align-items-center rounded">
                                            <?php
                                            echo $this->Form->control('ocorrencia_relatorios.' . $ocorrenciaIndex . '.id', [
                                                'value' => $ocorrenciasExistentes[$ocorrencia->id]['id'] ?? '',
                                                'type' => 'hidden'
                                            ]);
                                            echo $this->Form->control(
                                                'ocorrencia_relatorios.' . $ocorrenciaIndex . '.ocorrencia_id',
                                                [
                                                    'value' => $ocorrencia->id,
                                                    'checked' => in_array($ocorrencia->id, $ocorrenciasMarcadas ?? []),
                                                    'hiddenField' => false,
                                                    'type' => 'checkbox',
                                                    'class' => 'mx-2 form-check-input mt-0 rounded',
                                                    'id' => 'formGroupManut_' . $ocorrenciaIndex,
                                                    'label' => $ocorrencia->nm_tp_ocorrencia,
                                                    'templates' => [
                                                        'inputContainer' => '{{content}}',
                                                        'label' => '<label class="d-flex align-items-center m-0">{{input}} <span class="ms-2 mb-1">{{text}}</span></label>',
                                                        'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} style="height: 20px; width: 20px; ">'
                                                    ]
                                                ]
                                            );

                                            $ocorrenciaIndex++;
                                            // echo $this->Form->hidden('respostas.' . $j . '.resposta', [
                                            //     'value' => ''
                                            // ]);
                                            ?>
                                        </div>
                                    </div>

                                <?php } ?>
                            <?php endforeach; ?>
                        <?php } ?>
                        </div>
                    <?php
                    } ?>
                </div>
            </div>
            <?php if ($pergunta->codigo == 1) { ?>
                <div class="w-100 d-flex px-0">
                    <div class="col-3 my-2 pe-2">
                        <?= $this->Form->control('data', [
                            'type' => 'text', // Força o elemento a aceitar texto comum
                            'class' => 'px-3 mascara-data',
                            'label' => false,
                            'placeholder' => 'Data da visita',
                            'maxlength' => 10
                        ]); ?>
                    </div>
                    <div class="col-3 my-2">
                        <?= $this->Form->control('nm_atendido', [
                            'class' => 'px-3',
                            'label' => false,
                            'placeholder' => 'Nome de quem acompanhou:'
                        ]); ?>
                    </div>
                    <div class="col-6 ps-2 my-2">
                        <?= $this->Form->control('funcoes_id', [
                            'class' => 'text-secondary',
                            'empty' => 'Cargo de quem acompanhou: ',
                            'options' => $list_funcao,
                            'label' => false
                        ]); ?>
                    </div>
                </div>
                <div style="display: none;">
                    <?= $this->Form->control('ic_acompanhado', [
                        'type' => 'radio',
                        'id' => 'ic_acompanhado',
                        'options' => ['1' => 'Sim', '0' => 'Não'],
                        'label' => false
                    ]); ?>
                </div>
            <?php } ?>
            <?php if ($pergunta->codigo != 1) { ?>
                <div class="col-12 px-0">
                    <?= $this->Form->control('respostas.' . $i . '.observacao', [
                        'type' => 'textarea',
                        'rows' => 1,
                        'label' => false,
                        'placeholder' => 'Apontamentos:',
                        'class' => 'my-2 py-1 px-3 auto-resize'
                    ]); ?>
                </div>
            <?php } ?>
        </div>
    <?php endforeach; ?>

    <div class="d-flex align-items-center justify-content-between flex-wrap bg-danger text-white rounded py-2 mt-3 mb-2">
        <h6 class="mx-3 mb-0 text-question">
            Prioridade Alta?
        </h6>
    </div>
    <div class="col-6 ps-2 my-2">
        <?= $this->Form->control(
            'ic_prioridade',
            [
                'type' => 'radio',
                'options' => ['1' => 'Sim', '0' => 'Não'],
                // 'value' => null,
                'label' => false,
                'legend' => false,
                'div' => false,
                'templates' => [
                    'radioWrapper' => '<div class="d-inline  mx-3">{{label}}</div>',
                    'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class="form-check-input styled-radio mt-0 me-3">',
                    'label' => '<label class="form-check-label me-3">{{text}}</label>',
                ]
            ]
        ); ?>
    </div>

    <div class="bg-azul-40 py-2 my-2 rounded">
        <h6 class="mx-3 mb-0 text-white text-question">Responsável presente</h6>
    </div>
    <div class="w-100 d-flex px-0">


        <?php $this->Form->control(
            'ic_responsavel',
            [
                'type' => 'radio',
                'options' => ['1' => 'Sim', '0' => 'Não'],
                // 'value' => null,
                'label' => false,
                'legend' => false,
                'div' => false,
                'templates' => [
                    'radioWrapper' => '<div class="d-inline  mx-3">{{label}}</div>',
                    'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class="form-check-input styled-radio mt-0 me-3">',
                    'label' => '<label class="form-check-label me-3">{{text}}</label>',
                ]
            ]
        ); ?>

        <div class="col-6 my-2 ps-4 d-flex align-items-center gap-2">

            <span id="lblResponsavel">
                <?= $relatorio->responsavel_id ? 'Sim:' : 'Não:' ?>
            </span>

            <?php // ($relatorio->responsavel_id ? '<span>Sim: </span>' : '<span>Não: </span>') 
            ?>
            <?= $this->Form->control('responsavel_id', [
                'options' => $escola_users,
                'type' => 'select',
                'label' => false,
                'placeholder' => 'Nome do responsável:'
            ]); ?>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-center flex-wrap gap-2 my-4">

        <button class="btn btn-blue-40 rounded-5 text-white px-4" type="button" onclick="history.back();" style="width: 160px;">
            <i class="bi bi-arrow-left d-none d-md-inline"></i> VOLTAR
        </button>

        <?php if (!isset($relatorio->id) || (isset($relatorio->id) && $relatorio->getOriginal('ic_rascunho') == 1)) { ?>
            <button class="btn btn-blue-40 rounded-5 text-white px-4" name="ic_rascunho" data-value="1" type="button" style="width: 160px;" onclick="enviar_form(this);">
                <i class="bi bi-pencil-square d-none d-md-inline"></i> RASCUNHO
            </button>
        <?php } ?>

        <button class="btn btn-success rounded-5 text-white px-4" data-bs-toggle="tooltip" name="ic_rascunho" data-value="0" title="Finalizar/Assinar" type="button" style="width: 160px;" onclick="enviar_form(this);">
            <i class="bi bi-check-circle-fill d-none d-md-inline"></i> FINALIZAR
        </button>

    </div>

    <!-- BOTÃO -->
    <!-- <button class="btn btn-success w-100 my-3">Salvar</button> -->

</div>

<?= $this->Form->end(); ?>

<script>
    // document.addEventListener('input', function(e) {
    //     if (e.target.classList.contains('auto-resize')) {
    //         e.target.style.height = 'auto';
    //         e.target.style.height = e.target.scrollHeight + 'px';
    //     }
    // });
    document.addEventListener('DOMContentLoaded', function() {
        const textareas = document.querySelectorAll('.auto-resize');

        textareas.forEach(textarea => {
            // Ajusta a altura inicial com base no conteúdo existente
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';

            // Mantém o comportamento de ajustar enquanto digita
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {

        const select = document.getElementById('responsavel-id');
        const legenda = document.getElementById('lblResponsavel');

        function atualizarLegenda() {
            legenda.textContent = select.value ? 'Sim:' : 'Não:';
        }

        select.addEventListener('change', atualizarLegenda);

        // Atualiza ao carregar a página
        atualizarLegenda();

    });

    document.addEventListener('DOMContentLoaded', function() {

        const resposta0 = document.getElementById('respostas-0-resposta-0');
        const resposta1 = document.getElementById('respostas-0-resposta-1');
        const ic_acompanhado0 = document.getElementById('ic_acompanhado-0');
        const ic_acompanhado1 = document.getElementById('ic_acompanhado-1');

        resposta0.addEventListener('change', function() {
            if (resposta0.checked)
                ic_acompanhado0.checked = true;

            ic_acompanhado0.dispatchEvent(new Event('change'));
        })

        resposta1.addEventListener('change', function() {
            if (resposta1.checked)
                ic_acompanhado1.checked = true;

            ic_acompanhado1.dispatchEvent(new Event('change'));
        })
    });


    document.addEventListener('DOMContentLoaded', function() {
        const inputData = document.querySelector('.mascara-data');

        if (inputData) {
            inputData.addEventListener('input', function(e) {
                let valor = e.target.value;

                // 1. Remove tudo o que não for número
                valor = valor.replace(/\D/g, '');

                // 2. Validação do Dia (Não permite 00 nem maior que 31)
                if (valor.length >= 2) {
                    let dia = parseInt(valor.substring(0, 2), 10);
                    if (dia < 1) valor = '01' + valor.substring(2);
                    if (dia > 31) valor = '31' + valor.substring(2);
                }

                // 3. Validação do Mês (Não permite 00 nem maior que 12)
                if (valor.length >= 4) {
                    let mes = parseInt(valor.substring(2, 4), 10);
                    if (mes < 1) valor = valor.substring(0, 2) + '01' + valor.substring(4);
                    if (mes > 12) valor = valor.substring(0, 2) + '12' + valor.substring(4);
                }

                // 4. Trava estrita de tamanho (Máximo DDMMAAAA)
                if (valor.length > 8) {
                    valor = valor.substring(0, 8);
                }

                // 5. Aplica a formatação das barras
                if (valor.length > 2 && valor.length <= 4) {
                    valor = valor.replace(/^(\d{2})(\d+)/, '$1/$2');
                } else if (valor.length > 4) {
                    valor = valor.replace(/^(\d{2})(\d{2})(\d+)/, '$1/$2/$3');
                }

                e.target.value = valor;
            });

            // 6. Validação completa ao sair do campo (Regra do Ano e Calendário)
            inputData.addEventListener('blur', function(e) {
                let valor = e.target.value;

                if (valor.length === 10) {
                    let partes = valor.split('/');
                    let dia = parseInt(partes[0], 10);
                    let mes = parseInt(partes[1], 10);
                    let ano = parseInt(partes[2], 10);

                    let anoAtual = new Date().getFullYear();

                    // Validação do intervalo do ano (Mínimo 1900, Máximo Ano Atual)
                    if (ano < 1900 || ano > anoAtual) {
                        alert('Por favor, digite um ano válido entre 1900 e ' + anoAtual + '.');
                        e.target.value = '';
                        setTimeout(() => e.target.focus(), 10);
                        return;
                    }

                    // Cria um objeto de data real para checar o calendário
                    let dataTeste = new Date(ano, mes - 1, dia);

                    // Checa se o dia bate (Evita erros como 31/04 ou 29/02 em ano não bissexto)
                    if (dataTeste.getFullYear() !== ano || dataTeste.getMonth() + 1 !== mes || dataTeste.getDate() !== dia) {
                        alert('Esta data não existe no calendário.');
                        e.target.value = '';
                        setTimeout(() => e.target.focus(), 10);
                    }
                } else if (valor.length > 0 && valor.length < 10) {
                    alert('Por favor, digite a data completa (DD/MM/AAAA).');
                    setTimeout(() => e.target.focus(), 10);
                }
            });
        }
    });
</script>