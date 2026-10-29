<body style="text-align: justify">
    <h5 class="text-center">TERMO DE VISITA Nº <?= $relatorio->cd_termo . '/' . substr($relatorio->dt_relatorio, -4) ?></h5>
    <br>
    <?php //debug($relatorio); 
    ?>
    <p>
        <span>Estive, nesta data, no exercício de minhas funções, em visita de Supervisão à </span>
        <b class="text-uppercase"> <?= $relatorio->unid_escolare->nm_unid_escolar ?></b>,
        <span>tendo sido recebida pelo(a) Senhor(a)</span><b class="text-uppercase"> <?= $relatorio->nm_recibo ?></b>
        <span>ocupante do cargo/função </span><b class="text-uppercase"> <?= $relatorio->nm_cargo_recibo ?></b>
        <span>na unidade escolar.</span>
    </p>

    <b class="d-flex justify-content-center">ORGANIZAÇÃO/HIGIENE</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_organizacao ?></p>
    </div>

    <b class="d-flex justify-content-center">ATIVIDADES/ROTINAS(entrada/saída,banho,horário de soninho/recreio, sala de aula, etc)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_atividade ?></p>
    </div>

    <b class="d-flex justify-content-center">ALIMENTAÇÃO(cardápio, despensa, freezer, lactário, cozinha)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_alimentacao ?></p>
    </div>

    <b class="d-flex justify-content-center">MANUTENÇÃO(infraestrutura/SAU)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_manutencao ?></p>
    </div>

    <b class="d-flex justify-content-center">CAPACIDADE/Nº DE FUNCIONÁRIOS(ausência de servidores, capacidade das salas de aulas/salões)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_capacidade ?></p>
    </div>

    <b class="d-flex justify-content-center">LEGISLAÇÃO/DOCUMENTAÇÃO(Atos legais e escrituração escolar)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_legislacao ?></p>
    </div>

    <b class="d-flex justify-content-center">OUVIDORIA</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_ouvidoria ?></p>
    </div>

    <b class="d-flex justify-content-center">RECURSOS HUMANOS(conduta funcional e outras orientações)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_rh ?></p>
    </div>

    <b class="d-flex justify-content-center">PATRIMÔNIO(baixa de patrimônio, retirada de bens, solicitação de mobília/equipamentos, doações)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_patrimonio ?></p>
    </div>

    <b class="d-flex justify-content-center">OBSERVAÇÕES GERAIS(sala multifuncional, biblioteca, laboratório de informática e outras orientações que entender necessárias)</b>
    <div class="row row-cols-1 mb-3 g-0 border border-secondary">
        <p class="text-start ms-1 my-1"><?= $relatorio->ds_obs ?></p>
    </div>

    <b>Praia Grande,</b><span><?= $relatorio->dt_relatorio ?></span>

    <div class="row row-cols-2 my-2">
        <div class="col">
            <div class="text-center">
                <p class="d-flex justify-content-center mb-0">
                    <?= $this->Html->image('../files/' . $relatorio->cd_assinatura_supervisor, ['height' => '55']) ?>
                </p>
                <hr class="my-0">
                <p class="d-flex justify-content-center mb-0"><?= (isset($nm_supervisor) ? $nm_supervisor->nm_usuario : ''); ?> </p>
                <p class="d-flex justify-content-center">Supervisor (a) </p>
            </div>
        </div>
        <div class="col">
            <div class="text-center">
                <p class="d-flex justify-content-center mb-0">
                    <?= $this->Html->image('../files/' . $relatorio->cd_assinatura_diretor, ['height' => '55']) ?>
                </p>
                <hr class="my-0">
                <p class="d-flex justify-content-center mb-0">Diretor (a) </p>
                <p class="d-flex justify-content-center mb-0"><?= (isset($nm_diretor) ? $nm_diretor->nm_usuario : ''); ?> </p>
            </div>
        </div>

    </div>
    <div class="row row-cols-1 my-2 d-flex justify-content-center">

        <div class="col-6">
            <div class="text-center">
                <p class="d-flex justify-content-center mb-0">
                    <?= $this->Html->image('../files/' . $relatorio->cd_assinatura_admin, ['height' => '55']) ?>
                </p>
                <hr class="my-0">
                <p class="d-flex justify-content-center">Subsecretária de Gestão Pedagógica</p>
                <p class="d-flex justify-content-center"><?= (isset($nm_admin) ? $nm_admin->nm_usuario : ''); ?> </p>
            </div>
        </div>
    </div>

</body>