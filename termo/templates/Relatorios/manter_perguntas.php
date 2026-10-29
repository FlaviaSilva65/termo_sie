<?php if ($identity->isGestor || $identity->isSupervisor) {
    $finalizarPendAction = 'dash_escolas';
    $parametro = $escola_id ? $escola_id : $escola->id;
} elseif ($identity->isSupervisor) {
    $finalizarPendAction = 'dash_supervisor';
    $parametro = $identity->id;
}
?>
<?php if ($somentePendencias) { ?>
    <div class="col-12 col-lg-11 d-flex mx-auto mt-2 justify-content-center">
        <h6 class="badge rounded-pill bg-danger-subtle text-danger px-5 py-2">
            ACOMPANHAMENTO DE PENDÊNCIAS.
        </h6>
    </div>
<?php } ?>
<div class="col-12 col-lg-11 d-flex mx-auto">

    <?=
    $this->element('secoes_termo') .
        '<div class="w-100 p-0 p-lg-2">' .
        $this->element('perguntas') ?>
    <div class="bg-white p-2 rounded-bottom shadow d-flex justify-content-between align-items-center d-print-none">
        <?php if (empty($somentePendencias)) : ?>
            <?php /* $this->Html->link(
                '<i class="bi bi-arrow-left"></i>Etapa enterior',
                ['action' => 'manterPerguntas', $dimensao - 1, $escola_id, $relatorio->id],
                ['class' => 'btn btn-primary btn-sm btn-s-pill shadow link-navegacao ' . $desabilitaAnt . '', 'escape' => false]
            )*/ ?>

            <?= $this->element('btn_pill', [
                'label' => 'Etapa enterior',
                'cor' => 'primary',
                'icon'  => 'arrow-left',
                'url'   => ['action' => 'manterPerguntas', $dimensao - 1, $escola_id, $relatorio->id],
                'class' => 'btn-pill--fixed link-navegacao ' . $desabilitaAnt . '',
            ]) ?>

            <div class="">
                <?php if (empty($modoSomenteLeitura)): ?>
                    <?php
                    $emRascunho = (int)$relatorio->ic_rascunho === 1;
                    $labelSalvar = $emRascunho ? 'Salvar rascunho' : 'Salvar';
                    ?>
                    <?php /* $this->Html->link(
                        '<i class="bi bi-floppy me-2"></i>Salvar rascunho',
                        '/',
                        ['class' => 'btn btn-success btn-sm btn-s-pill shadow me-3 btn-salvar-rascunho ' . $desabilitaSalvarRasc . '', 'escape' => false]
                    ) */ ?>

                    <?= $this->element('btn_pill', [
                        'label' => $labelSalvar,
                        'cor' => 'success',
                        'icon'  => 'floppy',
                        'url'   => '#',
                        'class' => 'btn-pill--fixed btn-salvar-rascunho',
                    ]) ?>
                <?php endif; ?>
                <?php /* $this->Html->link(
                    'Próxima etapa<i class="bi bi-arrow-right ms-2"></i>',
                    ['action' => 'manterPerguntas', $dimensao + 1, $escola_id, $relatorio->id],
                    ['class' => 'btn btn-primary btn-sm btn-e-pill shadow link-navegacao ' . $desabilitaProx . '', 'escape' => false]
                ) */ ?>

                <?= $this->element('btn_pill', [
                    'label' => 'Próxima etapa',
                    'cor' => 'primary',
                    'icon'  => 'arrow-right',
                    'position' => 'end',
                    'url'   => ['action' => 'manterPerguntas', $dimensao + 1, $escola_id, $relatorio->id],
                    'class' => 'btn-pill--fixed link-navegacao ' . $desabilitaProx,
                ]) ?>

            </div>
        <?php else: ?>

            <?php /* $this->Html->link(
                '<i class="bi bi-arrow-left me-2"></i>Voltar ao painel',
                ['action' => 'dash-supervisor', $identity->id],
                ['class' => 'btn btn-primary btn-sm btn-s-pill shadow', 'escape' => false]
            ) */ ?>

            <?= $this->element('btn_pill', [
                'label' => 'Voltar ao painel',
                'cor' => 'primary',
                'icon'  => 'arrow-left',
                'url'   => ($identity->isSupervisor ? ['action' => 'dash-supervisor', $identity->id] : ['action' => 'dashEscolas', $escola_id]),
                'class' => 'btn-pill--fixed',
            ]) ?>

            <div class="">
                <?php
                $proximaComPendencia = null;
                foreach ($dimensoesComPendencia as $d) {
                    if ($d > $dimensao) {
                        $proximaComPendencia = $d;
                        break;
                    }
                }
                ?>
                <?php if ($proximaComPendencia): ?>
                    <?php /* $this->Html->link(
                        'Próxima pendência<i class="bi bi-arrow-right ms-2"></i>',
                        ['action' => 'manterPerguntas', $proximaComPendencia, $escola_id, $relatorio->id, '?' => ['pendencias' => 1]],
                        ['class' => 'btn btn-primary btn-sm btn-e-pill shadow link-secao', 'escape' => false]
                    ) */ ?>


                    <?= $this->element('btn_pill', [
                        'label' => 'Próxima pendência',
                        'cor' => 'primary',
                        'icon'  => 'arrow-right',
                        'position' => 'end',
                        'url'   => ['action' => 'manterPerguntas', $proximaComPendencia, $escola_id, $relatorio->id, '?' => ['pendencias' => 1]],
                        'class' => 'btn-pill--fixed link-secao',
                    ]) ?>


                <?php else: ?>
                    <?php /* $this->Html->link(
                        '<i class="bi bi-check-circle me-2"></i>Finalizar pendências',
                        ['action' => $finalizarPendAction, $parametro],
                        ['class' => 'btn btn-success btn-sm btn-s-pill shadow me-3', 'escape' => false]
                    ) */ ?>

                    <?= $this->element('btn_pill', [
                        'label' => 'Próxima pendências',
                        'cor' => 'primary',
                        'icon'  => 'arrow-right',
                        'position' => 'end',
                        'url'   => ['action' => 'dashEscolas', $escola_id],
                        'class' => 'btn-pill--fixed link-navegacao ' . ($dimensao == 6 ? 'disabled' : $desabilitaProx) . '',
                    ]) ?>


                    <?php /* $this->element('btn_pill', [
                            'label' => 'Finalizar pendências',
                            'cor' => 'success',
                            'icon'  => 'check-circle',
                            'url'   => ['action' => $finalizarPendAction, $parametro],
                            'class' => 'btn-pill--fixed',
                        ]) */ ?>

                    <?php //} 
                    ?>

                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Aviso -->

<div class="modal fade" id="modalAviso" tabindex="-1"
    aria-labelledby="modalAvisoTitulo" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalAvisoTitulo">
                    <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                    Atenção
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"></button>
            </div>

            <div class="modal-body pt-0">
                <p id="modalAvisoMensagem" class="mb-0"></p>
            </div>

            <div class="modal-footer border-0">
                <button type="button"
                    class="btn btn-primary btn-sm rounded-pill px-4"
                    data-bs-dismiss="modal">
                    Entendi
                </button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modalConfirmarAssinatura"
    tabindex="-1"
    aria-labelledby="modalConfirmarAssinaturaTitulo"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalConfirmarAssinaturaTitulo">
                    <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                    Confirmar assinatura
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"></button>
            </div>

            <div class="modal-body pt-0">
                <p class="mb-0">
                    Tem certeza de que deseja concluir e assinar este
                    Termo de Supervisão?
                </p>
            </div>

            <div class="modal-footer border-0">

                <button type="button"
                    class="btn btn-secondary btn-sm rounded-pill px-4"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button"
                    class="btn btn-success btn-sm rounded-pill px-4"
                    id="btnConfirmarAssinatura">
                    <i class="bi bi-check-circle me-1"></i>
                    Confirmar assinatura
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    const somentePendencias = <?= $somentePendencias ? 'true' : 'false' ?>;
    const modoSomenteLeitura = <?= !empty($modoSomenteLeitura) ? 'true' : 'false' ?>;
    const relatorioId = <?= (int)$relatorio->id ?>;
    const emRascunho = <?= (int)$relatorio->ic_rascunho === 1 ? 'true' : 'false' ?>;

    // Radio
    function atualizaResposta(radio) {
        const grupo = radio.closest('.grupo-resposta');
        // Limpa somente os radios desta pergunta
        grupo.querySelectorAll('label').forEach(function(label) {
            label.classList.remove('resposta-verde', 'resposta-vermelho', 'resposta-amarelo', 'resposta-laranja', 'resposta-azul');
        });
        const label = radio.closest('label');
        const resposta = label.textContent.trim().toLowerCase();
        const classes = {
            'sim': 'resposta-verde',
            'não': 'resposta-vermelho',
            'não verificado': 'resposta-amarelo',
            'adequado': 'resposta-verde',
            'inadequado': 'resposta-vermelho',
            'parcialmente adequado': 'resposta-laranja',
            'não se aplica': 'resposta-azul',
        };
        if (classes[resposta]) label.classList.add(classes[resposta]);
    }
    document.querySelectorAll('.grupo-resposta input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            atualizaResposta(this);
        });
        radio.addEventListener('click', function() {
            this.blur();
        });
    });
    document.querySelectorAll('.grupo-resposta input[type="radio"]:checked').forEach(function(radio) {
        atualizaResposta(radio);
    });

    // checkbox
    document.querySelectorAll('.checkbox-resposta input[type="checkbox"]').forEach(function(checkbox) {
        function atualizaCheckbox() {
            checkbox.closest('label').classList.toggle('resposta-verde', checkbox.checked);
        }
        checkbox.addEventListener('change', atualizaCheckbox);
        // Aplica a aparência caso já esteja marcado ao carregar
        atualizaCheckbox();
    });

    function erroObservacao(perguntaId, mostrar) {
        const campo = document.querySelector(`[name="respostas[${perguntaId}][observacao]"]`);
        const msg = document.getElementById(`erro-observacao-${perguntaId}`);

        if (campo) campo.classList.toggle('is-invalid', mostrar);
        if (msg) msg.classList.toggle('d-block', mostrar);
    }

    async function cancelarPendenciaAutomatica(perguntaId) {

        const hidden = document.querySelector(
            `input[name="respostas[${perguntaId}][status]"]`
        );

        // Só cancela se houver acompanhamento aberto
        if (!hidden || hidden.value !== '0') {
            return false;
        }

        const csrfToken = document.querySelector(
            'input[name="_csrfToken"]'
        )?.value;

        const ocorrenciasSelecionadas = Array.from(
            document.querySelectorAll(
                `input[type="checkbox"][name^="respostas[${perguntaId}][ocorrencias]"]:checked`
            )
        ).filter(checkbox => checkbox.value === '1');

        const possuiOcorrencias = ocorrenciasSelecionadas.length > 0;

        const response = await fetch(
            '/termo/relatorios/atualizarStatusResposta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    relatorio_id: relatorioId,
                    pergunta_id: perguntaId,
                    status: '2',
                    motivo: 'resposta_desmarcada',
                    modo_pendencias: '0',
                    possui_ocorrencias: possuiOcorrencias ? '1' : '0',
                    _csrfToken: csrfToken || ''
                })
            }
        );

        if (!response.ok) {
            throw new Error('Erro HTTP: ' + response.status);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(
                data.message || 'Não foi possível cancelar a pendência.'
            );
        }

        // Atualiza o status da resposta na View
        hidden.value = '1';

        // Atualiza o botão para Requerer
        const btn = Array.from(
            document.querySelectorAll('.btn-acompanhamento')
        ).find(b => b.dataset.perguntaId === String(perguntaId));

        if (btn) {

            btn.classList.remove('btn-warning');
            btn.classList.add('btn-success');

            const icon = btn.querySelector('.btn-pill__icon i');
            const texto = btn.querySelector('.js-acomp-texto');

            if (icon) {
                icon.classList.remove('bi-check-circle');
                icon.classList.add('bi-exclamation-circle');
            }

            if (texto) {
                texto.textContent = 'Requerer';
            }
        }

        return true;
    }

    function mostrarModalAviso(titulo, mensagem) {

        const modalElement = document.getElementById('modalAviso');

        if (!modalElement || !window.bootstrap?.Modal) {
            alert(`${titulo}\n\n${mensagem}`);
            return;
        }

        const tituloElement = document.getElementById('modalAvisoTitulo');
        const mensagemElement = document.getElementById('modalAvisoMensagem');

        if (tituloElement) {
            tituloElement.textContent = titulo;
        }

        if (mensagemElement) {
            mensagemElement.textContent = mensagem;
        }

        bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }

    function atualizarDataResposta(perguntaId, dataFormatada) {

        const elemento = document.querySelector(
            `[data-resposta-data="${perguntaId}"]`
        );

        if (!elemento || !dataFormatada) {
            return;
        }

        // Atualiza o ícone
        const icone = elemento.querySelector('i');

        if (icone) {
            icone.className = 'bi bi-check-circle me-2 fs-6 text-success';
        }

        // Atualiza a mensagem e a data
        const conteudo = elemento.querySelector('div');

        if (conteudo) {

            conteudo.innerHTML = `
        <p class="d-flex mb-0 fs-7 text-success lh-1">
            Respondida
        </p>

        <p class="text-nowrap mb-0 fs-8 text-secondary">
            em ${dataFormatada}
        </p>
    `;
        }
    }

    document.querySelectorAll('.btn-acompanhamento').forEach(function(btn) {

        btn.addEventListener('click', function() {

            const perguntaId = this.dataset.perguntaId;

            const hidden = document.querySelector(
                `input[name="respostas[${perguntaId}][status]"]`
            );

            if (!hidden) return;

            const resposta = document.querySelector(
                `input[name="respostas[${perguntaId}][resposta]"]:checked`
            );

            const observacao = document.querySelector(
                `[name="respostas[${perguntaId}][observacao]"]`
            );

            // Verifica as ocorrências selecionadas da pergunta
            const ocorrenciasSelecionadas = Array.from(
                document.querySelectorAll(
                    `input[type="checkbox"][name^="respostas[${perguntaId}][ocorrencias]"]:checked`
                )
            ).filter(checkbox => checkbox.value === '1');

            const possuiOcorrencias = ocorrenciasSelecionadas.length > 0;

            const statusAtual = String(hidden.value);

            let novoStatus;

            // Define a operação conforme o modo da página
            if (somentePendencias) {

                // Somente pendências abertas podem ser resolvidas
                if (statusAtual !== '0') return;

                novoStatus = '1';

            } else if (statusAtual === '0') {

                // Cancela uma pendência existente
                const confirmar = confirm(
                    'Deseja realmente cancelar esta pendência?'
                );

                if (!confirmar) return;

                novoStatus = '2';

            } else {

                // Requer um novo acompanhamento
                novoStatus = '0';
            }

            // Validação somente ao requerer acompanhamento
            if (novoStatus === '0') {

                const idPergunta = Number(perguntaId);

                const respostaValida = !!resposta ||
                    ([16, 18].includes(idPergunta) && possuiOcorrencias) ||
                    idPergunta === 20;

                if (!respostaValida) {
                    mostrarModalAviso(
                        'Resposta obrigatória',
                        'É necessário responder à pergunta antes de requerer acompanhamento.'
                    );
                    return;
                }

                // Observação obrigatória para todas
                if (!observacao || observacao.value.trim() === '') {

                    erroObservacao(perguntaId, true);

                    mostrarModalAviso(
                        'Observação obrigatória',
                        'Informe a observação antes de requerer acompanhamento.'
                    );

                    observacao?.focus();
                    return;
                }
            }

            if (observacao) {
                observacao.classList.remove('is-invalid');
            }

            const btnEl = this;
            const icon = btnEl.querySelector('.btn-pill__icon i');
            const texto = btnEl.querySelector('.js-acomp-texto');

            btnEl.disabled = true;

            const csrfToken = document.querySelector(
                'input[name="_csrfToken"]'
            )?.value;

            fetch('/termo/relatorios/atualizarStatusResposta', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    body: new URLSearchParams({
                        relatorio_id: relatorioId,
                        pergunta_id: perguntaId,
                        resposta: resposta ? resposta.value : '',
                        // Informa se existem checkboxes selecionados
                        possui_ocorrencias: possuiOcorrencias ? '1' : '0',
                        status: novoStatus,
                        observacao: observacao ? observacao.value.trim() : '',
                        modo_pendencias: somentePendencias ? '1' : '0',
                        _csrfToken: csrfToken || ''
                    })

                })
                .then(async res => {

                    const retorno = await res.text();

                    console.log('Status HTTP:', res.status);
                    console.log('Resposta do servidor:', retorno);

                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status + ': ' + retorno);
                    }

                    return JSON.parse(retorno);
                })
                .then(data => {

                    if (!data.success) {
                        mostrarModalAviso('Atenção',
                            data.message || 'Não foi possível atualizar o status.'
                        );

                        if (data.campo === 'observacao' && observacao) {
                            observacao.classList.add('is-invalid');
                            observacao.focus();
                        }

                        return;
                    }

                    console.log('Resposta do Controller:', data);
                    console.log('Pergunta:', perguntaId);
                    console.log('Data respondida:', data.respondido_em);
                    console.log(
                        'Elemento da data:',
                        document.querySelector(`[data-resposta-data="${perguntaId}"]`)
                    );

                    // Atualiza a data da resposta após salvar o acompanhamento
                    if (data.respondido_em && novoStatus === '0') {
                        atualizarDataResposta(perguntaId, data.respondido_em);
                    }

                    // Status da resposta: 0 = acompanhamento,
                    // 1 = sem acompanhamento
                    // Atualiza o status na View
                    hidden.value = novoStatus === '0' ? '0' : '1';

                    // -------------------------------------
                    // CANCELAMENTO DA PENDÊNCIA
                    // -------------------------------------
                    if (novoStatus === '2') {

                        btnEl.classList.remove('btn-warning');
                        btnEl.classList.add('btn-success');

                        if (icon) {
                            icon.classList.remove('bi-check-circle');
                            icon.classList.add('bi-exclamation-circle');
                        }

                        if (texto) {
                            texto.textContent = 'Requerer';
                        }

                        return;
                    }

                    // -------------------------------------
                    // RESOLUÇÃO DA PENDÊNCIA
                    // -------------------------------------
                    if (novoStatus === '1' && data.resolvido_em) {

                        const informacao = document.createElement('div');

                        informacao.className =
                            'd-flex justify-content-center align-items-center';

                        const icone = document.createElement('i');

                        icone.className =
                            'bi bi-check-circle text-success me-2 fs-6';

                        const conteudo = document.createElement('div');

                        const titulo = document.createElement('p');

                        titulo.className =
                            'mb-0 fs-7 text-success lh-1';

                        titulo.textContent = 'Resolvido';

                        const dataResolucao = document.createElement('p');

                        dataResolucao.className =
                            'text-nowrap mb-0 fs-8 text-secondary';

                        dataResolucao.textContent =
                            'em ' + data.resolvido_em;

                        conteudo.append(titulo, dataResolucao);

                        informacao.append(icone, conteudo);

                        btnEl.replaceWith(informacao);

                        return;
                    }

                    // -------------------------------------
                    // REQUER ACOMPANHAMENTO
                    // -------------------------------------
                    if (novoStatus === '0') {

                        btnEl.classList.remove('btn-success');
                        btnEl.classList.add('btn-warning');

                        if (icon) {
                            icon.classList.remove('bi-exclamation-circle');
                            icon.classList.add('bi-check-circle');
                        }

                        if (texto) {
                            texto.textContent = 'Em';
                        }
                    }

                })
                .catch(err => {

                    console.error('Erro ao atualizar status:', err);

                    alert('Erro de conexão ao atualizar status.');

                })
                .finally(() => {

                    btnEl.disabled = false;

                });

        });

    });

    document.querySelectorAll(
        'textarea[name^="respostas"][name$="[observacao]"]'
    ).forEach(function(textarea) {

        textarea.addEventListener('input', function() {

            if (this.value.trim() !== '') {
                const m = this.name.match(/^respostas\[(\d+)\]/);
                if (m) erroObservacao(m[1], false);
            }

        });

    });
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('form-perguntas'); // form principal (dentro do element perguntas)

        if (!form) return;

        function validarObservacoesPendentes() {
            const hiddens = form.querySelectorAll(
                'input[type="hidden"][name^="respostas["][name$="[status]"]'
            );

            for (const h of hiddens) {
                if (h.value !== '0') continue;

                const m = h.name.match(/^respostas\[(\d+)\]/);
                if (!m) continue;

                const campo = form.querySelector(`[name="respostas[${m[1]}][observacao]"]`);

                if (campo && campo.value.trim() === '') {
                    erroObservacao(m[1], true);
                    campo.focus();
                    campo.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return false;
                }

                const perguntaId = m[1];

                const radios = form.querySelectorAll(
                    `input[name="respostas[${perguntaId}][resposta]"]`
                );

                if (radios.length > 0) {

                    const selecionado = form.querySelector(
                        `input[name="respostas[${perguntaId}][resposta]"]:checked`
                    );

                    if (!selecionado) {
                        mostrarModalAviso(
                            'A pergunta está sem resposta e possui uma pendência aberta.',
                            true
                        );
                        radios[0].focus();
                        return false;
                    }
                }
            }
            return true;
        }

        function salvarEIrPara(url, ficarNaPagina = true) {
            // const campoData = form.querySelector('input[type="date"][name="relatorio[data]"]');

            // if (campoData && campoData.value && campoData.max && campoData.value > campoData.max) {
            //     campoData.classList.add('is-invalid');
            //     campoData.focus();
            //     mostrarFeedback('A data da visita não pode ser posterior à data de criação do relatório.', true);
            //     return;
            // }
            // if (campoData) campoData.classList.remove('is-invalid');

            // campoData.classList.add('is-invalid');

            if (!validarObservacoesPendentes()) {
                mostrarModalAviso('Preencha a observação das perguntas com acompanhamento requerido.');
                return;
            }

            const formData = new FormData(form);
            // formData.append('_ajax', '1'); // marcador explícito

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        // 'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => {
                            console.error('Resposta não-OK:', res.status, text);
                            throw new Error('HTTP ' + res.status);
                        });
                    }
                    return res.json();
                })

                .then(data => {
                    if (data.success) {
                        atualizarSecoes(data.contagem);

                        if (data.datas_respostas) {

                            Object.entries(data.datas_respostas).forEach(
                                ([perguntaId, dataResposta]) => {
                                    atualizarDataResposta(perguntaId, dataResposta);
                                }
                            );
                        }

                        if (url) {
                            window.location.href = url;
                        } else if (ficarNaPagina) {
                            mostrarModalAviso(emRascunho ?
                                'Rascunho salvo com sucesso!' :
                                'Salvo com sucesso!'
                            );

                        }
                    } else {
                        if (data.campo === 'observacao' && data.pergunta_id) {
                            erroObservacao(data.pergunta_id, true);
                        }

                        mostrarModalAviso(data.message || 'Erro ao salvar. Tente novamente.');
                    }
                })
                .catch(err => {
                    console.error('Erro no fetch:', err);
                    mostrarModalAviso('Erro de conexão.');
                });
        }

        function mostrarFeedback(msg, erro = false) {
            // Troque por um toast/notificação do seu design system
            alert(msg);
        }

        // Seções (coluna esquerda)
        document.querySelectorAll('.link-secao').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (somentePendencias || modoSomenteLeitura) {
                    window.location.href = this.href;
                } else {
                    salvarEIrPara(this.href);
                }

            });
        });

        // Etapa anterior / Próxima etapa
        document.querySelectorAll('.link-navegacao').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (modoSomenteLeitura) {
                    window.location.href = this.href;
                } else {
                    salvarEIrPara(this.href);
                }
            });
        });

        // Salvar rascunho — não navega
        const btnSalvar = document.querySelector('.btn-salvar-rascunho');
        if (btnSalvar) {
            btnSalvar.addEventListener('click', function(e) {
                e.preventDefault();
                salvarEIrPara(null, true);
            });
        }
    });

    document.querySelectorAll(
        'input[type="radio"][name^="respostas["]'
    ).forEach(function(radio) {

        radio.addEventListener('change', async function() {

            const match = this.name.match(
                /^respostas\[(\d+)\]\[resposta\]$/
            );

            if (!match) return;

            const perguntaId = match[1];

            const respostaSelecionada = document.querySelector(
                `input[name="respostas[${perguntaId}][resposta]"]:checked`
            );

            const hidden = document.querySelector(
                `input[name="respostas[${perguntaId}][status]"]`
            );

            if (!hidden || hidden.value !== '0') return;

            // Se ainda existe uma resposta selecionada,
            // mantém a pendência aberta
            if (respostaSelecionada) return;

            try {
                await cancelarPendenciaAutomatica(perguntaId);
            } catch (error) {
                console.error('Erro ao cancelar pendência:', error);
                alert(error.message);
            }
        });

    });

    function atualizarSecoes(contagem) {
        if (!contagem) return;
        document.querySelectorAll('.progress-ring[data-dimensao]').forEach(function(ring) {
            const dim = ring.dataset.dimensao;
            if (contagem.hasOwnProperty(dim)) {
                const novoAtual = parseInt(contagem[dim]);
                ring.dataset.current = novoAtual;
                renderProgress(ring, novoAtual, parseInt(ring.dataset.total));
            }
        });
        atualizaProgressBar();
    }

    document.addEventListener('DOMContentLoaded', function() {

        const btnAssinar = document.getElementById('btnAssinar');

        const modalElemento = document.getElementById(
            'modalConfirmarAssinatura'
        );

        const btnConfirmar = document.getElementById(
            'btnConfirmarAssinatura'
        );

        const formularioAssinatura = document.getElementById(
            'formAssinatura'
        );

        if (
            !btnAssinar ||
            !modalElemento ||
            !btnConfirmar ||
            !formularioAssinatura
        ) {
            console.warn('Elementos da assinatura não encontrados.');
            return;
        }

        const modalAssinatura = bootstrap.Modal.getOrCreateInstance(
            modalElemento
        );

        // Ao clicar em Concluir e Assinar, abre o Modal
        btnAssinar.addEventListener('click', function(event) {

            event.preventDefault();

            modalAssinatura.show();

        });

        // Ao confirmar, envia o formulário por POST
        btnConfirmar.addEventListener('click', function() {

            btnConfirmar.disabled = true;

            formularioAssinatura.submit();

        });

        // Ao cancelar, restaura o botão
        modalElemento.addEventListener('hidden.bs.modal', function() {

            btnConfirmar.disabled = false;

        });

    });
</script>