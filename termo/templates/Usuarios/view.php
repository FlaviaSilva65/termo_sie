<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">

<?= $this->Html->script(['custom']) ?>
<div class="col-6 mx-auto">

    <?= $this->Form->create($usuario, ['accept' => '.jpg', 'enctype' => 'multipart/form-data', 'novalidate']) ?>

    <div class="col-12 d-flex">
        <div class="col-2 pe-2">

            <?= $this->Form->control("cd_rf", [
                "class" => "col-2",
                "type" => "number",
                "onchange" => "busca_dados(this)",
                "label" => "RF"
            ]); ?>
        </div>
        <div class="col-6 ps-2">

            <?= $this->Form->control('nm_usuario', ['type' => 'text', 'class' => 'col-8', 'label' => 'Nome']); ?>
        </div>

    </div>
    <div class="col-12 d-flex">

        <div class="col-3 pe-2">
            <?= $this->Form->control('cd_cpf', ['class' => 'cpf', 'label' => 'CPF']); ?>
        </div>

        <div class="col-5 ps-2">
            <?= $this->Form->control('email', ['label' => 'E-mail']); ?>
        </div>

    </div>
    <div class="col-12 d-flex mb-3">

        <div class="col-4 pe-2">
            <?= $this->Form->control('password', ['label' => 'Senha']); ?>
        </div>
        <div class="col-4 ps-2">
            <?= $this->Form->control('tp_usuarios_id', ['label' => 'Grupo de Usuário', 'empty' => '--- ', 'options' => $tp_usuarios, 'onchange' => 'exibe_escola(this)']); ?>

        </div>

    </div>
    <div class="col-8" id="nm_escola">
        <label class="form-label">Escola(s)</label>

        <?php if (!empty($usuario->usuario_unid_escolares)): ?>

            <?php foreach ($usuario->usuario_unid_escolares as $vinculo): ?>

                <?php if (!empty($vinculo->unid_escolare)): ?>
                    <div class="form-control mb-2">
                        <?= h(
                            $vinculo->unid_escolare->sigla . ' ' . $vinculo->unid_escolare->nm_unid_escolar
                        ) ?>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="form-control text-secondary">
                Nenhuma escola vinculada
            </div>

        <?php endif; ?>
    </div>
    <!-- <div class="col-8 d-flex mt-3">
        <div class="navbar navbar-light" style="background-color: #e3f2fd;">
            <div class="container-fluid">Imagem</div>
            <div align="center">
                <?php
                $this->Html->image($assinatura->imagem ?? 'sf_3x2.jpg', [
                    'class' => 'w-25 cursor-pointer shadow',
                    'id' => 'img_foto',
                    'onClick' => 'upload_imagem.click()',
                    'title' => 'Clique para selecionar uma imagem !'
                ]) .
                    '<input type="file" name="upload_imagem" id="upload_imagem" style="display:none;" accept="image/*">' .
                    $this->Form->control('imagem', ['label' => '', 'class' => 'd-none'])
                ?>
                <br />
                <div id="uploaded_image"></div>
            </div>

        </div>
    </div> -->

    <?php $this->Form->button('<i class="fas fa-check mr-2"></i>Concluir', ['type' => 'submit', 'class' => 'btn btn-sm btn-success mr-3', 'escapeTitle' => false]) ?>

    <!-- Botão recortar imagem e realizar upload -->

    <div class="col-4 mt-4">
        <?= $this->Form->control('ic_ativo', ['type' => 'checkbox', 'class' => 'me-3', 'label' => ['text' => 'Ativo']]); ?>

    </div>

    <div class="d-flex justify-content-center mt-4">
        <button
            type="button"
            class="btn btn-primary btn-sm btn-s-pill shadow position-relative"
            style="width: 165px; height: 38px;"
            onclick="history.back()">
            <span class="icone-circulo">
                <i class="bi bi-arrow-left"></i>
            </span>
            <span>
                Voltar
            </span>
        </button>
    </div>
    <!-- <button class="btn btn-success px-4 ms-2" onclick="return ver_escola()" type="submit"><i class="bi bi-save"></i> </button> -->
</div>
<script>
    function ver_escola() {
        var escola = document.getElementById('nomeList').value;

        if (escola.length === 0) {

            document.getElementById('nomeList').focus();
            return false;

        }

    }

    async function busca_dados(el) {

        if (!el) {
            return;
        }

        try {
            const response = await fetch(`<?php echo $this->Url->build(['controller' => 'Usuarios', 'action' => 'carregarRf']); ?> ?rf=${el.value}`, {

                headers: {

                    'X-Requested-With': 'XMLHttpRequest'
                },

            });

            const resultado = await response.json();

            if (resultado.success) {
                document.getElementById('nm-usuario').value = resultado.data.name;
                document.getElementById('cd-cpf').value = resultado.data.tax;
                document.getElementById('email').value = resultado.data.email;

                document.getElementById('escola').value = resultado.data.places.shift();

                console.log('Recebido com sucesso:', resultado.mensagem);
            } else {
                alert('Erro ao atualizar: ' + resultado.mensagem);
            }
        } catch (erro) {
            console.error('Erro na comunicação com o servidor:', erro);
        }
    }


    function exibe_escola(el) {

        var elemento = el.value;
        // alert (elemento);
        if (elemento == 1 || elemento == 5) {
            var nm_escola = document.getElementById('nm_escola').classList.remove("d-none");
        } else {
            var nm_escola = document.getElementById('nm_escola').classList.add("d-none");
        }

    }
</script>
<style>
    .icone-circulo {
        position: absolute;
        left: 6px;
        top: 50%;
        transform: translateY(-50%);

        width: 28px;
        height: 28px;
        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        background-color: rgba(255, 255, 255, 0.2);
    }

    .icone-circulo i {
        font-size: 16px;
        color: #fff;
    }
</style>