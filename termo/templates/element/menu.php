<div class="col-11 mx-auto d-flex justify-content-between my-2 flex-wrap">
    <div class="col p-0 d-flex mb-2">

        <?php if ($identity->isSupervisor) {

            $this->Html->link(
                '<i class="bi bi-house me-lg-3" style="font-size: 1rem;"></i><p class="d-none d-lg-flex">INÍCIO</p>',
                ['controller' => 'Relatorios', 'action' => 'dashSupervisor', $setor->setores_id],
                ['escape' => false, 'class' => 'btn border border-1 rounded-0 me-1 me-sm-2 letter-space d-flex align-self-center', 'title' => 'Início']
            );
        } elseif ($identity->isSupervisor) {

            $this->Html->link(
                '<i class="bi bi-house me-lg-3" style="font-size: 1rem;"></i><p class="d-none d-lg-flex">INÍCIO</p>',
                ['controller' => 'Relatorios', 'action' => 'dashSubsecretaria'],
                ['escape' => false, 'class' => 'btn border border-1 rounded-0 me-1 me-sm-2 letter-space d-flex align-self-center', 'title' => 'Início']
            );
        } elseif ($identity->isGestor) {
            $this->Html->link(
                '<i class="bi bi-house me-lg-3" style="font-size: 1rem;"></i><p class="d-none d-lg-flex">INÍCIO</p>',
                ['controller' => 'Relatorios', 'action' => 'dashDiretorEscolas', $identity->id],
                ['escape' => false, 'class' => 'btn border border-1  rounded-0 me-1 me-sm-2 letter-space d-flex align-self-center', 'title' => 'Início']
            );
        }
        ?>
        <!-- <div class="col d-flex justify-content-end justify-content-md-start">
            <h5 class="d-flex align-items-center my-0 ms-2" style="line-height: 0.8em;"><?= $funcao->nm_tp_usuarios ?>&nbsp;<?= explode(' ', $identity->nm_usuario)[0] ?></h5>
        </div> -->
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("campo-ano");
        const form = document.getElementById("busca_relatório");

        select.addEventListener("change", function() {
            // alert(select.value);
            if (this.value) {
                form.submit();
            }
        });
    });
</script>


<!-- 
    #6c757d rgba(108, 117, 125, 1) cor secondary
    #212529 rgba(33,37,41,1 ) cor dark 
-->