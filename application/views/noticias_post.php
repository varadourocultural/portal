
            <article class="news">

                <section class="row">
                    <div class="grid-12 news-single-header">

                        <h2>Notícias</h2>

                    <?php
                        if ($noticia->fotos):
                    ?>

                        <img src="<?php echo site_url("/publico/thumb/{$noticia->fotos[0]->foto_file_id}/1028") ?>" alt="<?php echo $noticia->titulo; ?>" width="1028" style="height: auto">

                    <?php
                        endif;
                    ?>

                    </div>
                </section>

                <section class="row">

                    <article class="grid-12 news-wrapper">

                        <div class="news-single-content">
                        <?php
                            $data = strtotime($noticia->data);
                        ?>
                            <h1><?php echo $noticia->titulo; ?></h1>

                            <section class="news-single-meta-info">
                                <span class="date"><?php echo date('d/m/Y', $data); ?></span>
                                <span>postado por <?php echo $noticia->autor; ?></span>

                                <div class="addthis_toolbox addthis_default_style ">
                                    <a class="addthis_button_preferred_1"></a>
                                    <a class="addthis_button_preferred_2"></a>
                                    <a class="addthis_button_preferred_3"></a>
                                    <a class="addthis_button_preferred_4"></a>
                                    <a class="addthis_button_compact"></a>
                                    <a class="addthis_counter addthis_bubble_style"></a>
                                </div>
                            </section>

                            <?php
                                echo $noticia->conteudo;
                            ?>

                            <footer class="news-single-footer">
                                <h4>Deixe seu comentário</h4>

                                <div class="fb-comments" data-href="<?php echo site_url('/noticias'). '/'. urlencode($noticia->slug); ?>" data-numposts="5" data-colorscheme="light"></div>
                            </footer>

                        </div>

                        <div class="news-social-sidebar">
                            <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fvaradourocultural&amp;width&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:290px;" allowTransparency="true"></iframe>
                        </div>

                    </article>

                </section>

                <div class="row">

                    <div class="grid-12 news-related">
                        <h3>Mais notícias</h3>
                    </div>

                    <?php
                        foreach ($mais_noticias as $noticia):
                            $data = strtotime($noticia->data);
                    ?>

                    <article class="news-single grid-4">
                        <a href="<?php echo site_url('/noticias'). '/'. urlencode($noticia->slug); ?>">
                            <figure>
                            <?php
                                if($noticia->fotos):
                            ?>
                                <img src="<?php echo site_url("/publico/image/{$noticia->fotos[0]->foto_file_id}") ?>" alt="<?php echo $noticia->titulo; ?>" width="325" height="200">

                            <?php
                                endif;
                            ?>
                                <figcaption>
                                    <h3 class="news-title"><?php echo $noticia->titulo; ?></h3>

                                    <span class="news-date"><?php echo date('d/m/Y', $data); ?></span>

                                    <p class="news-summary"><?php echo character_limiter(strip_tags_better($noticia->conteudo), 200); ?></p>
                                </figcaption>
                            </figure>
                        </a>
                    </article>

                    <?php
                        endforeach;
                    ?>
                </div>

            </article>
