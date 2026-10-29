<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport">

    <?php

    use function PHPUnit\Framework\isNull;

    if ($this->request->getParam('action') == 'relatorioPdf') { ?>
        <title>Termos - <?= 'nº ' . $relatorio->cd_termo . ' ' . substr($relatorio->dt_relatorio, -7) ?> </title>
    <?php } else { ?>
        <title>Termos - <?= 'nº ' . $relatorio->termo_id . ' ' . $relatorio->data->format('(m/Y)') ?> </title>
    <?php } ?>



    <?= $this->Html->meta('favicon.png', '/favicon.png', ['type' => 'icon']) ?>

    <?= $this->Html->css(['bootstrap.min', 'bootstrap_icon_min', 'printer', 'main']) ?>
    <?php // $this->Html->script(['default']) 
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

</head>

<body class="position-relative">
    <div class="page-container">
        <!-- <div class="d-flex justify-content-center mb-3 no-print">
            <button class="btn btn-sm btn-primary me-3" onclick="print()" style="width: 176px;">
                <i class="bi bi-printer"></i>
                <span>Imprimir</span>
            </button>
            <?php if (is_null($relatorio->ic_cancelado) && $identity->isSupervisor && !empty($relatorio->id_ass_dir)) : ?>
                <button class="btn btn-sm btn-danger ms-3"
                    onclick="cancelar('<?= $relatorio->id ?>','<?= $this->Url->build(['controller' => 'Relatorios', 'action' => 'cancelar']) ?>', '<?= $this->request->getAttribute('csrfToken') ?>')"
                    style="width: 176px;">
                    <i class="bi bi-file-earmark-x"></i>
                    <span>Tornar sem efeito</span>
                </button>
            <?php endif; ?>
        </div> -->
        <div class="page-content">
            <div class="print container-fluid">
                <nav class="header text-black font-aller">
                    <div class="d-grid pb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <?= $this->Html->image('brasao-preto.svg', ['height' => '90']) ?>
                            <div class="container-fluid d-grid gap-2 justify-content-center">
                                <?= $this->Html->image('timbre-texto.svg', ['height' => '40']) ?>
                                <span class="text-center text-uppercase fw-normal">Secretaria de Educação</span>
                            </div>
                        </div>
                    </div>
                </nav>
                <main class="text-black font-lato">
                    <?= $this->fetch('content') ?>
                </main>
                <div class="container-fluid">
                    <hr class="border-0 py-4">
                    <footer class="footer text-black font-aller">
                        <div class="d-grid justify-content-center">
                            <span class="text-center fw-normal">Av. Presidente Kennedy, 9.000 • Mirim • Praia Grande • www.praiagrande.sp.gov.br</span>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    let print = event => window.print()

    function cancelar(id, url, token) {
        if (!confirm("Tem certeza que deseja tornar este relatório sem efeito?")) {
            return;
        }

        if (!token) {
            console.error('Token CSRF não encontrado nos cookies.');
            alert('Erro de sessão. Por favor, atualize a página.');
            return;
        }

        // alert(id);
        // alert(url);

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': token
                },
                body: JSON.stringify({
                    id_relatorio: id
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro no servidor: Status ${response.status}');
                }

                return response.json();
            })
            .then(data => {
                if (data.sucesso) {
                    alert("Status atualizado com sucesso!");
                    location.reload();
                } else {
                    alert("Erro: " + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert("Erro ao processar a requisição.");
            });
    }
</script>