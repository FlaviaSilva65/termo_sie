<div class="col-12 mx-auto d-flex my-5 justify-content-between align-items-stretch flex-wrap">

    <!-- Aguardando assinatura -->
    <!-- r_ic => se é rascunho ou não -->

    <?= $this->element('card', ['titulo' => 'AGUARDANDO ASSINATURA', 'tit_icone' => 'clock', 'cor' => true, 'action' => 'sign', 'r_ic' => 0, 'ass_sub' => '', 'num_col' => 'col-lg-6 pe-4']); ?>

    <!-- Racunho -->
    <?= $this->element('card', ['titulo' => 'RASCUNHOS', 'tit_icone' => 'pencil-square', 'cor' => false, 'action' => 'add', 'r_ic' => 1, 'ass_sub' => false , 'num_col' => 'col-lg-6']); ?>

</div>
<script>
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

    const escolaSelect = document.getElementById('escola-id');
    const btnNovoTermo = document.getElementById('btnNovoTermo');
    const btnVerUnidade = document.getElementById('btnVerUnidade');

    function controlarBotoes() {
        const selecionado = escolaSelect.value !== '';

        btnNovoTermo.disabled = !selecionado;
        btnVerUnidade.disabled = !selecionado;
    }

    escolaSelect.addEventListener('change', controlarBotoes);

    controlarBotoes();
</script>