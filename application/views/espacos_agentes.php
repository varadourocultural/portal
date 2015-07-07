
            <div class="row">
                <section class="grid-8 space">

                    <h2 class="side-heading -inline">Espaços</h2>

                    <div class="espacos-result">
                    <?php $this->load->view('_partials/_espacos', array('espacos' => $espacos)); ?>
                    </div>

                </section>

                <section class="grid-4 sidebar agent -listing">

                    <h2 class="side-heading -inline">Agentes</h2>

                    <div class="agentes-result">
                    <?php $this->load->view('_partials/_agentes', array('agentes' => $agentes)); ?>
                    </div>

                </section>
            </div>

            <div class="row">
                <div class="grid-12">
                <?php if($has_more): ?>
                    <a href="<?php echo site_url('/espacos_agentes_ajax'); ?>" class="load-more -red" data-controller="espacos-agentes" data-start-espacos="<?php echo $start_espacos; ?>" data-start-agentes="<?php echo $start_agentes; ?>" data-load-espacos="<?php echo $has_more_espacos; ?>" data-load-agentes="<?php echo $has_more_agentes; ?>">Abrir mais espaços e agentes</a>
                <?php endif; ?>
                </div>
            </div>
