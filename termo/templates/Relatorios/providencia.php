<?= $this->Form->create(null, [
    'url' => ['action' => 'salvarProvidencias'],
    'type' => 'post',
    'id' => 'formProvidencias'
]) ?>
<div class="container my-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><span>Data</span></th>
                                <th><span>Termo</span></th>
                                <th><span>Pergunta</span></th>
                                <th><span>Pendência</span></th>
                                <?php if (isset($identity) && $identity->isSupervisor) { ?>
                                    <th><span>Feito</span></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php // $this->Form->hidden($usuario_id, [ 'type' => 'hidden']); 
                            ?> <!-- $identity->id -->
                            <?php foreach ($providencias as $providencia) :  ?>
                                <tr>
                                    <td>
                                        <?= $providencia->relatorio->data; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $providencia->relatorio->termo_id; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $providencia->pergunta->codigo; ?>
                                    </td>
                                    <td>
                                        <div
                                            class="d-flex justify-content-between align-items-center"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseProvidencia<?= $providencia->id; ?>"
                                            aria-expanded="false" aria-controls="collapseProvidencia<?= $providencia->id ?>"
                                            style="cursor: pointer;">
                                            <span style="font-size: 13px;"><?= $providencia->pergunta->descricao; ?></span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <div class="collapse" id="collapseProvidencia<?= $providencia->id ?>">
                                            <div class="bg-light p-2">
                                                <span class="text-danger">
                                                    <?= h($providencia->observacao) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <?php if (isset($identity) && $identity->isSupervisor) { ?>
                                        <td class="text-center align-middle">
                                            <input type="checkbox"
                                                name="status[<?= $providencia->id; ?>]"
                                                value="1"
                                                style="transform: scale(1.2); cursor: pointer;">
                                        </td>
                                    <?php } ?>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (isset($identity) && $identity->isSupervisor) { ?>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        Resolvidos
                    </button>
                </div>
            <?php } ?>

        </div>
    </div>
    <div class="toast-container position-absolute top-50 start-50 translate-middle-x">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="providenciaToast">
            <div class="toast_body p-2 text-center bg-alert fw-bold">
                Selecione pelo menos uma prodivência.
            </div>
        </div>
    </div>
</div>

<?= $this->Form->end() ?>
<script>
    const toastEl = document.getElementById('providenciaToast');
    const toast = bootstrap.Toast.getOrCreateInstance(toastEl);

    document.getElementById('formProvidencias').addEventListener('submit', function(e) {
        const selecionados = document.querySelectorAll(
            'input[name^="status"]:checked'
        );

        if (selecionados.length === 0) {
            e.preventDefault();

            toast.show();
        }
    })
</script>