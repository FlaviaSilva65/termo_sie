<?= $this->Html->script(['jquery.maskedinput', 'jquery.mask']); ?>
<div class="container d-flex justify-content-center position-relative my-5 top-100 start-0">
    <div class="col-4 position-absolute mt-4 p-2 border border-info rounded-4 bg-azul">
        <span class="text-white d-flex justify-content-center">LOGIN</span>
    </div>
    <div class="col-12 mt-5 d-flex justify-content-center">
        <div class="col-6 p-4 border border-info rounded-5">
            <?= $this->Form->create() ?>

            <div class="col-12 border border-info rounded-3 bg-azul d-flex align-items-center">
                <div class="col-2 d-flex justify-content-center ">
                    <span class="text-white">Login</span>
                </div>

                <div class="col-10 border border-info rounded-3">
                    <?= $this->Form->control('username', [
                        'id' => 'login-input',
                        'label' => false,
                        'placeholder' => 'CPF ou Login',
                        'maxlength' => 14
                    ]) ?>
                </div>
            </div>

            <div class="col-12 border border-info rounded-3 bg-azul d-flex align-items-center my-2">

                <div class="col-2 d-flex justify-content-center ">
                    <span class="text-white">Senha</span>
                </div>

                <div class="col-10 border border-info rounded-3">
                    <?= $this->Form->control('password', ['label' => false]) ?>
                </div>

            </div>
            <div class="col-12 d-flex justify-content-center mt-5">
                <div>
                    <button class="btn btn-success rounded-5 text-white px-5">ENTRAR</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>