<div class="row col-11 mx-auto">
    <?php
    foreach ($titulos as $key => $titulo):
        echo
        '<div class="col-12 col-sm-6 col-lg-4 col-xxl-3 g-3 d-flex">
                <div class="w-100 d-flex flex-column">
                    <div class="d-flex align-items-center bg-dark-primary rounded-top px-2 shadow overflow-hidden">' .
            $this->Html->link(
                '<div class="d-flex align-items-center min-width-0">
                            <div class="bg-white rounded-circle d-flex justify-content-center flex-shrink-0" style="width:1.4rem;height:1.4rem;">' . ($key + 1) . '</div>
                            <h6 class="mb-0 p-2 text-white text-truncate">' . $titulo->texto . '</h6>
                        </div>',
                [
                    'action' => 'dashEscolas',
                    $titulo->escola_id
                ],
                ['class' => 'flex-grow-1 min-width-0 overflow-hidden', 'escape' => false,]
            ) .
            $this->Html->link(
                '<div class="rounded-pill alert alert-danger fs-7 px-1 mb-0 text-nowrap">
                    ' . $titulo->pendencias . ' 
                    <span class="d-none d-xl-inline">Ações Pendentes</span>
                </div>',
                [
                    'action' => 'pendencias',
                    $titulo->escola_id
                ],
                ['escape' => false,]
            ) .

            '</div>
                    <div class="bg-white text-dark rounded-bottom p-2 shadow flex-grow-1">
                        <div class="w-100 bg-white py-1 boder-bottom d-flex justify-content-between fs-7">
                            <div class="col-3 px-0">
                                <p class="text-dark-primary me-3">' . count($titulo->itens) . ' Termos</p>
                            </div>
                            <div class="col-4 text-center px-0">
                                <p class="text-dark-primary"> Criado em: </p>
                            </div>
                            <div class="col-5 text-end pe-2">
                                <p class="text-dark-primary me-2"> Situação</p>
                            </div>
                            
                        </div>';
        foreach ($titulo->itens as $item):

            if ($item->situacao == 1) {
                $cor = 'danger';
                $msg = 'Pendente de Assinatura';
            } elseif ($item->situacao == 2) {
                $cor = 'success';
                $msg = 'Assinado';
            } else {
                $cor = 'warning';
                $msg = 'Rascunho';
            }

            $temAssinatura = !empty($item->id_ass_dir) || !empty($item->id_ass_assis);

            $urlItem = $temAssinatura
                ? ['action' => 'visualizarPdf', $item->id]
                : ['action' => 'manterPerguntas', 1, $titulo->escola_id, $item->id];

            echo
            $this->Html->link(
                '<div class="row align-items-center py-2 mx-0 hover rounded border-top border-light-secondary">
                    <div class="col-3 d-flex align-items-center px-0">
                    <i class="bi bi-circle-fill text-' . $cor . ' me-2 fs-9"></i>
                        <h6 class="mb-0 text-dark">' . str_pad($item->termo_id, 3, '0', STR_PAD_LEFT) . '/' . $item->data->format('Y') . '</h6>
                    </div>' .
                    '<div class="col-4 text-center px-0">
                        <h6 class="mb-0 text-dark">' . $item->data . '</h6>
                        </div>' .
                    '<div class="col-5 d-flex justify-content-end px-o">
                        <span class="alert alert-' . $cor . ' rounded-pill text-center text-nowrap mb-0 py-1 px-2 fs-8">' . $msg . '
                        </span>
                    </div>

                </div>',
                $urlItem,
                ['escape' => false]
            );
        endforeach;
        echo
        '</div>
                </div>
            </div>';
    endforeach;
    ?>
</div>