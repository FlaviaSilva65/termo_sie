<?= $this->Form->create($relatorio, ['id' => 'formRelatorio']) ?>
<?php $date = date('Y-m-d'); ?>

<?= $this->Form->hidden('usuario_id', ['value' => $identity->id]) .
    $this->Form->hidden('unid_escolar_id', ['value' => $escola->id]) .
    $this->Form->hidden('data', ['value' => $date]); ?>


<div class="col-11 col-lg-11 mx-auto d-flex justify-content-between my-2 flex-wrap poppins">
    <div class="col-12 ps-3 bg-white d-flex align-items-center rounded-3">
        <span class="fs-4 letter-space text-secondary"><?= $escola->nome_completo ?></span>
    </div>
    <div class="col-6 pe-3 my-3">
        <p>Atendido por:</p>
        <div class="col-12 bg-white d-flex justify-content-between p-2 rounded-3">
            <?= $this->Form->control('nm_atendido', [
                'class' => 'border-0',
                'type' => 'text',
                'id' => 'nome',
                'label' => false,
                'placeholder' => 'Foi atendido por quem?',
                'templates' => 'template/form'
            ]); ?>
        </div>
    </div>
    <div class="col-6 ps-3 my-3">
        <p>Cargo/função:</p>
        <div class="col-12 bg-white d-flex justify-content-between p-2 rounded-3">
            <?= $this->Form->control('funcoes_id', [
                'class' => 'border-0',
                'empty' => '--- ',
                'options' => $list_funcao,
                'label' => false,
                'type' => 'select',
                'templates' => 'template/form'
            ]); ?>
        </div>
    </div>

    <div class="col-12 top-purple-bar my-2 d-flex justify-content-center align-items-center">
        <h5 class="text-white letter-space fw-bold mb-0 ms-3">ACOMPANHAMENTO GERAL</h5>
    </div>
    <div class="col-sm-12 col-md-4 pe-md-2">
        <div class="flex-wrap bg-striped py-2 my-2">
            <h6 class="d-flex text-center mx-3 fw-bold">
                Houve acompanhamento na entrada ou saída dos alunos pela equipe gestora?
            </h6>
        </div>

        <div class="w-100 d-flex align-items-center justify-content-center flex-wrap bg-striped py-2 my-2">
            <?= $this->Form->control(
                'ic_acompanhado',
                [
                    'type' => 'radio',
                    'options' => ['1' => 'Sim', '0' => 'Não'],
                    'value' => null,
                    'label' => false,
                    'legend' => false,
                    'templates' => [
                        'radioWrapper' => '<div class="d-inline mx-3">{{label}}</div>',
                        'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class="form-check-input styled-radio mt-0 me-2">',
                    ]
                ]
            ); ?>
        </div>

        <div class="col-12 mb-2" id="ds_acomp" style="display: none">
            <?= $this->Form->control('ds_acompanhado', ['class' => 'col-12 rounded-0', 'label' => 'Observação sobre o Acompanhamento']); ?>
        </div>
        <button class="w-100 d-flex justify-content-center align-items-center bg-green-40 me-3 py-2 border-0" onclick="exibe_div(this)" id="acomp" type="button">
            Adicionar Observação
        </button>
    </div>

    <div class="col-12 col-md-4 px-md-2">
        <div class="d-flex justify-content-center align-items-center bg-striped py-2 my-2" style="height: 64px;">
            <h6 class="d-flex mx-3 mb-0 fw-bold">
                Organização:
            </h6>
        </div>
        <div class="w-100 d-flex align-items-center justify-content-center flex-wrap bg-striped py-2 my-2">
            <?= $this->Form->control(
                'tipo_organizacao',
                [
                    'type' => 'radio',
                    'options' => ['1' => 'Satisfatoria', '0' => 'Insatisfatório'],
                    'value' => null,
                    'label' => false,
                    'legend' => false,
                    'templates' => [
                        'radioWrapper' => '<div class="d-inline mx-3">{{label}}</div>',
                        'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class="form-check-input styled-radio mt-0 me-2">',
                    ]
                ]
            ); ?>
        </div>
        <div class="col-12 mb-2" id="ds_org" style="display: none">
            <?= $this->Form->control('ds_organizacao', ['class' => 'col-12 rounded-0', 'label' => 'Observação sobre a Organização']); ?>
        </div>
        <button class="w-100 d-flex justify-content-center align-items-center bg-green-40 me-3 py-2 border-0" onclick="exibe_div(this)" id="org" type="button">
            Adicionar Observação
        </button>
    </div>

    <div class="col-12 col-md-4 ps-md-2">
        <div class="d-flex align-items-center justify-content-center flex-wrap bg-striped py-2 my-2" style="height: 64px;">
            <h6 class="mx-3 mb-0 fw-bold">
                Higiene:
            </h6>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center flex-wrap bg-striped py-2 my-2">

            <?= $this->Form->control(
                'tipo_higiene',
                [
                    'type' => 'radio',
                    'options' => ['1' => 'Satisfatoria', '0' => 'Insatisfatório'],
                    'value' => null,
                    'label' => false,
                    'legend' => false,
                    'templates' => [
                        'radioWrapper' => '<div class="d-inline mx-3">{{label}}</div>',
                        'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class="form-check-input styled-radio mt-0 me-2">',

                    ]
                ]
            ); ?>
        </div>

        <div class="col-md-12 mb-2" id="ds_hig" style="display: none">
            <?= $this->Form->control('ds_higiene', ['class' => 'col-12 rounded-0', 'label' => 'Observação sobre a Higiene']); ?>
        </div>
        <button class="w-100 d-flex justify-content-center align-items-center bg-green-40 py-2 border-0" onclick="exibe_div(this)" id="hig" type="button">
            Adicionar Observação
        </button>
    </div>
    <div class="col-12 bottom-purple-bar mt-2"></div>

    <div class="col-12 d-flex justify-content-between flex-wrap">
        <div class="col-12 col-md-4 pe-md-3 mt-4">
            <div class="col-12 top-purple-bar my-2 d-flex justify-content-center align-items-center">
                <div class="col-3 d-flex justify-content-center">
                    <?= $this->Html->image('manutencao.svg', ['style' => 'width: 2rem;']); ?>
                </div>
                <div class="col-9 d-flex justify-content-between ps-5">
                    <h5 class="text-white letter-space fw-bold mb-0 ms-3">MANUTENÇÃO</h5>
                </div>
            </div>
            <div class="col-12 d-flex flex-wrap">

                <?php foreach ($list_manutencao as $i => $ocorrencia) : ?>
                    <?php // if ($ocorrencia->categorias_id == 3) { 
                    ?>
                    <?php // debug($relatorio->ocorrencia_relatorios); 
                    ?>
                    <div class="col-6 col-md-6 bg-striped my-1">
                        <div class="p-2 ">
                            <?php
                            // echo $this->Form->checkbox('list_manutencao[]', [
                            //     'value' => $ocorrencia->id,
                            //     'checked' => in_array($ocorrencia->id, $ocorrenciasRelacionadasIds),
                            // ])

                            echo $this->Form->control('ocorrencia_relatorios.' . $i . '.id', ['type' => 'hidden']);
                            echo $this->Form->control(
                                'list_manutencao[]',
                                [
                                    'value' => $ocorrencia->id,
                                    'checked' => in_array($ocorrencia->id, $ocorrenciasRelacionadasIds),
                                    'hiddenField' => false,
                                    'type' => 'checkbox',
                                    'class' => 'me-2 formcontrol',
                                    'id' => 'formGroupManut',
                                    'label' => $ocorrencia->nm_tp_ocorrencia,
                                    'templates' => [
                                        'radioWrapper' => '<div class="border border-danger d-inline mx-3">{{label}}</div>',
                                        'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',

                                    ]
                                ]
                            );                               ?>
                            <?php // h($ocorrencia->nm_tp_ocorrencia) 
                            ?>
                        </div>
                    </div>
                    <?php // } 
                    ?>
                <?php endforeach; ?>
            </div>

            <div class="col-md-12 mb-2" id="ds_manut" style="display: none">
                <?= $this->Form->control('ds_manutencao', ['class' => 'col-12 rounded-0', 'label' => 'Observação sobre a Manutenção']); ?>
            </div>
            <button class="w-100 d-flex justify-content-center align-items-center bg-green-40 py-2 my-1 border-0" onclick="exibe_div(this)" id="manut" type="button">
                Adicionar Observação
            </button>
            <div class="col-12 bottom-purple-bar mt-2"></div>
        </div>

        <div class="col-12 col-md-8 ps-md-3 mt-4">
            <div class="col-12 top-purple-bar my-2 d-flex justify-content-center align-items-center">
                <div class="col-3 ps-5">
                    <?= $this->Html->image('legislacao.svg', ['style' => 'width: 3rem;']); ?>
                </div>
                <div class="text-white col-9 ps-5">
                    <h5 class="letter-space fw-bold mb-0">LEGISLAÇÃO / DOCUMENTAÇÃO</h5>
                </div>

            </div>
            <div class="col d-flex flex-wrap">

                <?php foreach ($list_legislacao as $x => $ocorrencia) : ?>
                    <?php // if ($ocorrencia->categorias_id == 4) { 
                    ?>
                    <?php // debug($relatorio->ocorrencia_relatorios); 
                    ?>
                    <div class="col-12 col-md-4 text-start bg-striped my-1">
                        <div class="p-2 ">
                            <?php

                            echo $this->Form->control('ocorrencia_relatorios.' . $x . '.id', ['type' => 'hidden']);
                            echo $this->Form->control(
                                'list_legislacao[]',
                                [
                                    'value' => $ocorrencia->id,
                                    'checked' => in_array($ocorrencia->id, $ocorrenciasRelacionadasIds),
                                    'hiddenField' => false,
                                    'type' => 'checkbox',
                                    'class' => 'me-2 formcontrol',
                                    'id' => 'formGroupManut',
                                    'label' => $ocorrencia->nm_tp_ocorrencia
                                ]
                            );

                            ?>
                        </div>
                    </div>
                    <?php // } 
                    ?>
                <?php endforeach; ?>
            </div>

            <div class="col-md-12 mb-2" id="ds_leg" style="display: none">
                <?= $this->Form->control('ds_legislacao_documentos', ['class' => 'col-12 rounded-0', 'label' => 'Observação sobre a Legislação e Documentação']); ?>
            </div>
            <button class="w-100 d-flex justify-content-center align-items-center bg-green-40 py-2 border-0" onclick="exibe_div(this)" id="leg" type="button">
                Adicionar Observação
            </button>
            <div class="col-12 bottom-purple-bar mt-2"></div>
        </div>
        <div class="col-12 col-md-8 pe-md-3 mt-4">
            <div class="col-12 top-purple-bar my-2 d-flex justify-content-center align-items-center">
                <h5 class="text-white letter-space fw-bold mb-0">OBSERVAÇÕES GERAIS</h5>
            </div>
            <div class="col-md-12" id="ds_obs_geral">
                <?= $this->Form->control('ds_obs_geral', ['class' => 'col-12 rounded-0 border-0', 'label' => false]); ?>
            </div>
            <div class="col-12 bottom-purple-bar"></div>

            <div class="d-flex align-items-center justify-content-between flex-wrap bg-danger text-white rounded py-2 mt-3 mb-2">
                <h6 class="mx-3 mb-0">
                    Prioridade Alta?
                </h6>
                <?= $this->Form->control(
                    'ic_prioridade',
                    [
                        'type' => 'radio',
                        'options' => ['1' => 'Sim', '0' => 'Não'],
                        'value' => null,
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
        </div>
        <div class="col-12 col-md-4 ps-md-3 mt-md-4">
            <div class="col-12 top-purple-bar my-2 ps-md-3 d-flex justify-content-center align-items-center">
                <h5 class="text-white letter-space fw-bold mb-0">RESPONSÁVEL</h5>
            </div>
            <?= $this->Form->control('responsavel_id', ['class' => 'rounded-0 mb-2 border-0', 'options' => $escola_users, 'type' => 'select', 'label' => 'Nome:']); ?>
            <div class="d-flex align-items-center justify-content-between flex-wrap bg-striped mb-2 py-3">
                <h6 class="mx-3 mb-0">
                    Estava presente?
                </h6>
                <?= $this->Form->control(
                    'ic_responsavel',
                    [
                        'type' => 'radio',
                        'options' => ['1' => 'Sim', '0' => 'Não'],
                        'value' => null,
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
            <div class="col-12 bottom-purple-bar"></div>
        </div>
    </div>
    <div class="col-12 d-flex justify-content-center flex-wrap my-4">

        <div class="col-1 d-flex justify-content-center align-items-center flex-wrap border-0 rounded px-5 pt-3 mx-2 btn-blue-40" onclick="history.back()">
            <?= $this->Html->image('seta.svg'); ?>
            <p class="mb-1">VOLTAR</p>
        </div>


        <div class="col-1 d-flex justify-content-center align-items-center flex-wrap btn-blue-40 border-0 rounded px-5 pt-3 mx-2"
            name="ic_rascunho" data-value="1" type="button" onclick="enviar_form(this);">

            <?= $this->Html->image('rascunho.svg'); ?>
            <p class="mb-1">RASCUNHO</p>

        </div>

        <div class="col-1 d-flex justify-content-center align-items-center flex-wrap btn-green-40 border-0 rounded px-5 pt-3 mx-2"
            data-bs-toggle="tooltip" name="ic_rascunho" data-value="0" title="Finalizar/Assinar" type="button" onclick="enviar_form();">

            <?= $this->Html->image('finalizar.svg'); ?>
            <p class="mb-1">FINALIZAR</p>

        </div>
    </div>
</div>

<?= $this->Form->end() ?>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Finalizar e Assinar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span>Você deseja realmente assinar? </span>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btnYes">Sim</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btnNo">Não</button>
            </div>
        </div>
    </div>
</div>

<script>
    // function enviar_form() {
    //     var myform = document.getElementById("formRelatorio");
    //     myform.submit();
    // }


    // function exibe_div(el) {
    //     const div = el.id;

    //     switch (div) {
    //         case "acomp":
    //             const acomp = document.getElementById('ds-acompanhado')
    //             if (document.getElementById('ds_acomp').style.display === "none") {
    //                 document.getElementById('ds_acomp').style.display = "block";
    //                 acomp.focus();
    //             } else {
    //                 document.getElementById('ds_acomp').style.display = "none";
    //             }
    //             break;
    //         case "org":
    //             const org = document.getElementById('ds-organizacao');
    //             if (document.getElementById('ds_org').style.display === "none") {
    //                 document.getElementById('ds_org').style.display = "block";
    //                 org.focus();
    //             } else {
    //                 document.getElementById('ds_org').style.display = "none";
    //             }
    //             break;
    //         case "hig":
    //             const hig = document.getElementById('ds-higiene');
    //             if (document.getElementById('ds_hig').style.display === "none") {
    //                 document.getElementById('ds_hig').style.display = "block";
    //                 hig.focus();
    //             } else {
    //                 document.getElementById('ds_hig').style.display = "none";
    //             }
    //             break;
    //         case "manut":
    //             const manut = document.getElementById('ds-manutencao');
    //             if (document.getElementById('ds_manut').style.display === "none") {
    //                 document.getElementById('ds_manut').style.display = "block";
    //                 manut.focus();
    //             } else {
    //                 document.getElementById('ds_manut').style.display = "none";
    //             }
    //             break;
    //         case "leg":
    //             const leg = document.getElementById('ds-legislacao-documentos');
    //             if (document.getElementById('ds_leg').style.display === "none") {
    //                 document.getElementById('ds_leg').style.display = "block";
    //                 leg.focus();
    //             } else {
    //                 document.getElementById('ds_leg').style.display = "none";
    //             }
    //             break;
    //     }
    // }



    // const form = document.getElementById('formRelatorio');

    // function enviarFormulario() {

    //     const formData = new FormData(form);

    //     fetch('http://localhost/termo/relatorios/add/<?php // $escola->id 
                                                        ?> ', {
    //             method: 'POST',
    //             body: formData
    //         })
    //         .then(response => response.text())
    //         .then(data => {
    //             console.log('Formulário enviado com sucesso!', data);
    //         })
    //         .catch(error => {
    //             console.error('Erro ao enviar o formulário:', error);
    //         });
    // }

    // setInterval(enviarFormulario, 30000);

    // const formulario = document.getElementById('formRelatorio');

    //     function enviarDadosAoServidor() {

    //         // alert (formulario);
    //         const formData = new FormData(formulario);
    //         const dadosArray = Array.from(formData.entries()).map(entry => {
    //             return {
    //                 nome: entry[0],
    //                 valor: entry[1]
    //             };
    //         });

    //         const jsonData = JSON.stringify(formData);
    //         console.log("Enviando JSON:", json)


    //     fetch('/Relatorios/add', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //         },
    //         body: JSON.stringify(formData)
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         console.log('Dados salvos no servidor:', data)
    //     })
    //     .catch(error => {
    //         console.error('Erro ao enviar os dados:', error);
    //     });
    // }


    /** Esse código funcionou bem, mas ele recarrega a página */
    // setInterval(function() {
    //     document.getElementById('formRelatorio').submit();
    // }, 30000);

    // const formulario = document.getElementById('formRelatorio'); // Substitua 'meuFormulario' pelo ID do seu formulário
    // const intervalo = 10000; // Tempo em milissegundos (10 segundos, por exemplo)
    // let dados;
    // let timer;

    // function salvarFormulario() {
    //     // Coletar dados do formulário
    //     const formData = new FormData(formulario);
    //     const dadosArray = Array.from(formData.entries()).map(entry => {
    //         return {
    //             nome: entry[0],
    //             valor: entry[1]
    //         };
    //     });

    //     // Enviar dados para o PHP (exemplo com XMLHttpRequest)
    //     const xhr = new XMLHttpRequest();
    //     xhr.open('POST', 'salvar_formulario.php', true);
    //     xhr.setRequestHeader('Content-Type', 'application/json');

    //     xhr.onload = function() {
    //         if (xhr.status >= 200 && xhr.status < 300) {
    //             console.log('Formulário salvo com sucesso!');
    //         } else {
    //             console.error('Erro ao salvar o formulário.');
    //         }
    //     };

    //     xhr.onerror = function() {
    //         console.error('Erro de rede ao enviar o formulário.');
    //     };

    //     xhr.send(JSON.stringify(dadosArray)); // Envia os dados como JSON
    // }

    // timer = setInterval(salvarFormulario, intervalo);

    // // Parar o timer ao enviar o formulário ou fechar a página
    // formulario.addEventListener('submit', () => {
    //     clearInterval(timer);
    // });

    // window.addEventListener('beforeunload', () => {
    //     clearInterval(timer);
    // });
</script>