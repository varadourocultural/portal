
            <article class="events">

                <section class="row">
                    <div class="grid-8 no-padding">

                    <?php
                        if ($evento_primario) :
                    ?>

                        <article class="grid-12 event-single -featured">
                            <a href="<?php echo site_url('/evento'). '/'. urlencode($evento_primario->slug); ?>">
                                <figure>

                                <?php
                                    if ($evento_primario->imagem_cover_file_id):
                                ?>
                                    <img src="<?php echo site_url("/publico/thumb/{$evento_primario->imagem_cover_file_id}/676/356"); ?>" alt="<?php echo $evento_primario->titulo; ?>" width="325" height="224">

                                <?php
                                    endif;
                                ?>

                                    <figcaption>
                                        <span class="meta-info"><?php echo $evento_primario->espaco->nome_espaco; ?></span>

                                        <h3><?php echo $evento_primario->titulo ?></h3>

                                        <span class="meta-info"><?php echo $evento_primario->informacoes_datas; ?></span>
                                        <span class="meta-info"><?php echo $evento_primario->informacoes_horarios; ?></span>
                                        <span class="meta-info"><?php echo $evento_primario->informacoes_valores; ?></span>
                                    </figcaption>
                                </figure>
                            </a>
                        </article>

                    <?php
                        endif;
                    ?>

                    </div>
                    <div class="grid-4 sidebar">

                        <h2 class="side-heading -inline">Agenda cultural</h2>

                    <?php if($u = $this->session->userdata('usuario_site')): ?>
                        <a class="agenda-link-small" href="<?php echo site_url('/usuario/'.$u->username.'/agenda'); ?>">Monte sua programação</a>
                    <?php endif; ?>

                        <span class="datepicker-title">Selecione por dia</span>

                        <div class="datepicker" id="datepicker"></div>

                    </div>

                </section>

                <div class="agenda-result">
                <?php $this->load->view('_partials/_eventos', array('eventos' => $eventos)); ?>
                </div>

                <div class="row">
                    <div class="grid-12">
                    <?php if($has_more): ?>
                        <a href="<?php echo site_url('/agenda_ajax'); ?>" class="load-more -blue" data-controller="agenda" data-start="<?php echo $start; ?>">Abrir mais eventos</a>
                    <?php endif; ?>
                    </div>
                </div>

            </article>
