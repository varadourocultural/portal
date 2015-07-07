
            <div class="row">
                <section class="grid-8 search">

                    <h2 class="side-heading -inline">Buscar</h2>
                    <h3>Resultados para <span><?php echo $q ?></span></h3>

                    <form id="search-filters" method="get" action="<?php echo site_url('/busca_ajax'); ?>" class="search-filter">

                        <h4>Filtrar por</h4>
                        <input type="hidden" name="q" value="<?php echo $q; ?>">
                        <input type="hidden" name="start" value="<?php echo $start; ?>">

                        <input type="checkbox" name="t[]" value="noticia" id="news" class="prettyCheckable" data-label="Notícias">
                        <input type="checkbox" name="t[]" value="evento" id="agenda" class="prettyCheckable" data-label="Agenda">
                        <input type="checkbox" name="t[]" value="agente_cultural" id="agents" class="prettyCheckable" data-label="Agentes">
                        <input type="checkbox" name="t[]" value="espaco_cultural" id="spaces" class="prettyCheckable" data-label="Espaços">

                    </form>

                    <ul class="search-results">
                    <?php $this->load->view('_partials/_resultado_busca', array('itens' => $itens)); ?>
                    </ul>

                </section>
            </div>

            <div class="row">
                <div class="grid-12">
                <?php if($has_more): ?>
                    <a href="#" class="load-more -red" data-controller="busca">Abrir mais resultados</a>
                <?php endif; ?>
                </div>
            </div>
