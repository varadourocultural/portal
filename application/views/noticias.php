
            <article class="news">

                <section class="row">
                    <div class="grid-8 no-padding">

                    <?php
                        $noticia_primaria = $noticias[0];
                        $noticia_secundaria = $noticias[1];

                        unset($noticias[0]);
                        unset($noticias[1]);

                        $data = strtotime($noticia_primaria->data);
                    ?>

                    <?php if($noticia_primaria): ?>
                        <article class="grid-12 news-single -featured ">
                            <a href="<?php echo site_url('/noticias'). '/'. urlencode($noticia_primaria->slug); ?>">
                                <figure>
                                    <img src="<?php echo site_url("/publico/thumb/{$noticia_primaria->fotos[0]->foto_file_id}/676/315"); ?>" alt="<?php echo $noticia_primaria->titulo; ?>" width="676" height="315">

                                    <figcaption>
                                        <h3 class="news-title"><?php echo $noticia_primaria->titulo; ?></h3>

                                        <span class="news-date"><?php echo date('d/m/Y', $data); ?></span>

                                        <p class="news-summary"><?php echo character_limiter(strip_tags_better($noticia_primaria->conteudo), 200); ?></p>
                                    </figcaption>
                                </figure>
                            </a>
                        </article>
                    <?php endif; ?>

                    </div>
                    <div class="grid-4 sidebar">

                        <h2 class="side-heading -inline">Notícias</h2>

                    <?php
                        $data = strtotime($noticia_secundaria->data);
                    ?>

                    <?php if($noticia_secundaria): ?>
                        <article class="grid-12 news-single">
                            <a href="<?php echo site_url('/noticias'). '/'. urlencode($noticia_secundaria->slug); ?>">
                                <figure>
                                    <img src="<?php echo site_url("/publico/thumb/{$noticia_secundaria->fotos[0]->foto_file_id}/325/200"); ?>" alt="<?php echo $noticia_secundaria->titulo; ?>" width="325" height="200">

                                    <figcaption>
                                        <h3 class="news-title"><?php echo $noticia_secundaria->titulo; ?></h3>

                                        <span class="news-date"><?php echo date('d/m/Y', $data); ?></span>

                                        <p class="news-summary"><?php echo character_limiter(strip_tags_better($noticia_secundaria->conteudo), 200); ?></p>
                                    </figcaption>
                                </figure>
                            </a>
                        </article>
                    <?php endif; ?>

                    </div>

                </section>

                <div class="noticias-result">
                <?php $this->load->view('_partials/_noticias', array('noticias' => $noticias)); ?>
                </div>

                <div class="row">
                    <div class="grid-12">
                    <?php if($has_more): ?>
                        <a href="<?php echo site_url('/noticias_ajax'); ?>" class="load-more -blue" data-controller="noticias" data-start="<?php echo $start; ?>">Abrir mais notícias</a>
                    <?php endif; ?>
                    </div>
                </div>

            </article>
