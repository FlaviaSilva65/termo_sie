<div class="p-2 d-print-none" style="width:20rem;">
    <div class="w-100 bg-white p-2 rounded-top shadow">
        <p class="mb-0 fs-6 mb-0 fw-bold text-primary">SEÇÕES DO TERMO</p>
        <p class="mb-0 fs-7 text-secondary qtdRespondidas"></p>
        <div class="d-flex">
            <div class="w-100 progress me-2">
                <div class="progress-bar" id="progressTotal" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h6 class="lh-1 fw-bold text-primary porcentagem"></h6>
        </div>
    </div>
    <?php
    foreach ($dimensoes as $d):
        // Modo pendências: mostra só as seções que têm pendência
        if (!empty($somentePendencias) && !in_array($d->dimensao, $dimensoesComPendencia ?? [])) {
            continue;
        }

        $urlSecao = array_merge(
            ['action' => 'manterPerguntas', $d->dimensao, $escola_id, $relatorio->id],
            !empty($somentePendencias) ? ['?' => ['pendencias' => 1]] : []
        );

        $qtdPendenciasDimensao = $pendenciasPorDimensao[$d->dimensao] ?? 0;

        echo
        $this->Html->link(
            '<div class="w-100 d-flex border border-light align-items-center justify-content-between btn-hover shadow">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary my-3 ms-2 me-2 d-flex align-items-center justify-content-center" style="width:1.3rem;height:1.3rem;">
                            <h6 class="mb-0 text-white">' . $d->dimensao . '</h6>
                        </div>
                        <h6 class="mb-0 small text-nowrap text-dark">' . $d->titParcial . '<br>
                            <span class="text-secondary fs-7">' .
                $d->pergTotal . ' Perguntas
                                <button type="button" class="border-0 p-0 bg-transparent"
                                    data-bs-container="body"
                                    data-bs-toggle="popover"
                                    data-bs-trigger="hover"
                                    data-bs-custom-class="custom-popover-blue"
                                    data-bs-html="true"
                                    data-bs-placement="top"
                                    data-bs-content="<div class=\'w-100 rounded bg-info ps-2 text-center\'>Dimensão ' . $romanos[$d->dimensao] . '</div>' . $d->titCompleto . '">
                                    <i class="bi bi-info-circle small ms-1 text-primary"></i>
                                </button>
                            </span>' .
                ($qtdPendenciasDimensao > 0
                    ? '<br><span class="badge bg-danger rounded-pill fs-8">' . $qtdPendenciasDimensao . ' pendência' . ($qtdPendenciasDimensao > 1 ? 's' : '') . '</span>'
                    : '')
                .
                '</h6>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="progress-ring my-1 me-1" data-dimensao="' . $d->dimensao . '" data-current="' . $d->pergRespondidas . '" data-total="' . $d->pergTotal . '" data-size="60" data-stroke="5"></div>
                        <div class="rounded me-1 barra-hover"></div>
                    </div>
                </div>',
            $urlSecao,
            ['class' => 'link-secao', 'escape' => false]
        );
    endforeach;
    echo '<div class="bg-white rounded-bottom text-center p-2 shadow">';

    switch ($assinaturaUsuario['status']) {
        case 'assinado':
            echo '<span class="btn btn-success btn-sm btn-s-pill shadow disabled">
                <i class="bi bi-check-circle me-1"></i>Assinado
              </span>';
            break;

        case 'pode_assinar':

            echo '<button type="button"
                    id="btnAssinar"
                    class="btn btn-success btn-sm btn-s-pill shadow btn-abrir-assinatura d-none">
                    <i class="bi bi-check-circle me-1"></i>Concluir e Assinar
                </button>';

            echo $this->Form->create(null, [
                'id' => 'formAssinatura',
                'url' => [
                    'controller' => 'Relatorios',
                    'action' => 'concluirAssinatura',
                    $relatorio->id
                ],
                'style' => 'display:none;'
            ]);

            echo $this->Form->end();

            echo '<h6 id="msgConcluir" class="mb-0 small text-danger d-inline-block">
                    <b><i class="bi bi-exclamation-circle me-1"></i></b>
                    Conclua todas as seções para assinar
                </h6>';

            break;

        case 'rascunho':
            echo '<h6 class="mb-0 small text-secondary">
                <i class="bi bi-hourglass-split me-1"></i>Aguardando o supervisor finalizar o termo
              </h6>';
            break;

        case 'aguardando_gestores':
            $faltam = $assinaturaUsuario['faltam'];
            $texto  = empty($faltam)
                ? 'Nenhum Diretor ou Assistente cadastrado para esta unidade'
                : 'Aguardando assinatura do ' . implode(' e do ', $faltam);

            echo '<h6 class="mb-0 small text-danger">
                <b><i class="bi bi-exclamation-circle me-1"></i></b>' . h($texto) . '
              </h6>';
            break;
    }

    echo '</div>';
    ?>
</div>
<script>
    // Inicializa o popover
    popover();

    // Barra Circular
    document.querySelectorAll('.progress-ring').forEach(el => {
        const current = parseInt(el.dataset.current);
        const total = parseInt(el.dataset.total);
        renderProgress(el, current, total);
    });

    function renderProgress(container, current, total) {
        //const radius = 32;
        const size = parseInt(container.dataset.size) || 90;
        const stroke = parseInt(container.dataset.stroke) || 7;
        // deixa uma pequena margem para não cortar o círculo
        const padding = stroke;
        const radius = (size / 2) - padding;
        const fontSize = size * 0.22;
        const center = size / 2;
        const circumference = 2 * Math.PI * radius;
        let percent = current / total;
        // Compensa a ponta arredondada
        const ajuste = (stroke / circumference) * 0.60;
        if (current > 0 && current < total) percent -= ajuste;
        percent = Math.max(0, percent);
        const offset = circumference * (1 - percent);
        const concluido = total > 0 && current >= total;
        const color =
            concluido ?
            '#198754' :
            '#FFC107';
        const text =
            concluido ?
            '<i class="bi bi-check-lg text-success fs-4"></i>' :
            `${current}/${total}`;
        const gradientId = 'grad-' + Math.random().toString(36).substring(2, 10);
        container.innerHTML = `
            <svg width="${size}" height="${size}" viewBox="0 0 ${size} ${size}">
                <defs>
                    <linearGradient id="${gradientId}" gradientUnits="userSpaceOnUse" x1="0" y1="${size}" x2="${size}" y2="0" gradientTransform="rotate(-90 ${center} ${center})">
                        <stop offset="0%" stop-color="#FFC107"/>
                        <stop offset="100%" stop-color="#FD7E14"/>
                    </linearGradient>
                </defs>
                <circle
                    cx="${center}"
                    cy="${center}"
                    r="${radius}"
                    fill="none"
                    stroke="#e9ecef"
                    stroke-width="${stroke}">
                </circle>
                <circle
                    cx="${center}"
                    cy="${center}"
                    r="${radius}"
                    fill="none"
                    stroke="${current >= total ? '#198754' : `url(#${gradientId})`}"
                    stroke-width="${stroke}"
                    stroke-linecap="${current >= total ? 'butt' : 'round'}"
                    transform="rotate(-90 ${center} ${center})"
                    stroke-dasharray="${circumference}"
                    stroke-dashoffset="${offset}">
                </circle>
            </svg>
            <div class="ring-text" style="font-size:${fontSize}px">
                ${text}
            </div>
        `;
    }

    // Atualiza a progress bar
    function atualizaProgressBar() {
        let total = 0;
        let current = 0;

        document.querySelectorAll('[data-total][data-current]').forEach(item => {
            total += Number(item.dataset.total);
            current += Number(item.dataset.current);
        });

        const percent = total > 0 ?
            Math.round((current / total) * 100) :
            0;

        const bar = document.getElementById('progressTotal');

        if (!bar) return;

        // Atualiza largura
        bar.style.width = percent + '%';
        bar.setAttribute('aria-valuenow', percent);

        // Texto da barra (opcional)
        //bar.textContent = `${current}/${total}`;
        document.getElementsByClassName('porcentagem')[0].innerHTML = percent + '%';
        document.getElementsByClassName('qtdRespondidas')[0].innerHTML = current + ' de ' + total + ' respondidas';

        // Amarelo Bootstrap -> Verde Bootstrap
        const btnAssinar = document.getElementById('btnAssinar');
        const msgConcluir = document.getElementById('msgConcluir');

        if (percent >= 100) {
            bar.style.background = '#198754';
            if (btnAssinar) btnAssinar.classList.replace('d-none', 'd-inline-block');
            if (msgConcluir) msgConcluir.classList.replace('d-inline-block', 'd-none');
        } else {
            bar.style.background = 'linear-gradient(90deg, #FFC107 0%, #FD7E14 100%)';
            if (btnAssinar) btnAssinar.classList.replace('d-inline-block', 'd-none');
            if (msgConcluir) msgConcluir.classList.replace('d-none', 'd-inline-block');
        }
    }
    atualizaProgressBar();
</script>