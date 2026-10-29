<div class="header">
    <div class="header-container">
        <div class="header-content d-flex align-items-center justify-content-between py-2 px-3 py-md-4 px-sm-5">
            <div class="header-brasao d-none d-sm-block">
                <?= $this->Html->image('brasao-x-pg-azul.svg', ['alt' => 'Praia Grande', 'class' => 'd-none d-lg-block py-2 py-md-0']) ?>
                <?= $this->Html->image('brasao-azul.svg', ['alt' => 'Praia Grande', 'class' => 'd-block d-lg-none py-2 py-md-0']) ?>
            </div>
            <div class="header-title d-none d-md-grid ">
                <a href="<?= $this->Url->build(['action' => 'index']) ?>">
                    <div class="header-title-grid">
                        <h2 class="header-title-head">
                            <span>S</span>
                            <span>i</span>
                            <span>s</span>
                            <span>t</span>
                            <span>e</span>
                            <span>m</span>
                            <span>a</span>
                            <span>s</span>
                        </h2>
                        <small class="header-title-foot"><span>T</span>
                            <span>e</span>
                            <span>r</span>
                            <span>m</span>
                            <span>o</span>

                            <span>&nbsp;</span>

                            <span>d</span>
                            <span>e</span>

                            <span>&nbsp;</span>

                            <span>S</span>
                            <span>u</span>
                            <span>p</span>
                            <span>e</span>
                            <span>r</span>
                            <span>v</span>
                            <span>i</span>
                            <span>s</span>
                            <span>ã</span>
                            <span>o</span></small>
                    </div>
                </a>
            </div>
            <div class="header-title header-title-sm d-grid d-md-none">

                <div class="header-title-grid">
                    <span class="header-title-head">
                        <span>S</span>
                        <span>i</span>
                        <span>s</span>
                        <span>t</span>
                        <span>e</span>
                        <span>m</span>
                        <span>a</span>
                        <span>s</span></span>
                    <span class="header-title-foot"><span>T</span>
                        <span>e</span>
                        <span>r</span>
                        <span>m</span>
                        <span>o</span>

                        <span>&nbsp;</span>

                        <span>d</span>
                        <span>e</span>

                        <span>&nbsp;</span>

                        <span>S</span>
                        <span>u</span>
                        <span>p</span>
                        <span>e</span>
                        <span>r</span>
                        <span>v</span>
                        <span>i</span>
                        <span>s</span>
                        <span>ã</span>
                        <span>o</span></span>
                </div>
                </a>
            </div>
            <div class="header-logo">
                <a href="<?= $this->Url->build('https://www.cidadaopg.sp.gov.br') ?>">
                    <?= $this->Html->image('logo-cidadao-pg-azul.svg', ['alt' => 'Cidadão PG', 'class' => 'py-2 py-md-0']) ?>
                </a>
            </div>
        </div>
        <?= $this->element('navbar') ?>
    </div>
</div>