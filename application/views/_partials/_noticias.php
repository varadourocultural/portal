
            <?php

                if(count($noticias) > 9) {
                    array_pop($noticias);
                }

                $noticias = array_chunk($noticias, 3);

                foreach ($noticias as $noticias_row):
            ?>

                <section class="row">

                <?php
                    foreach ($noticias_row as $noticia):
                        $data = strtotime($noticia->data);
                ?>

                    <article class="news-single grid-4">
                        <a href="<?php echo site_url('/noticias'). '/'. urlencode($noticia->slug); ?>">
                            <figure>

                                <?php
                                    if ($noticia->fotos):
                                ?>

                                <img src="<?php echo site_url("/publico/thumb/{$noticia->fotos[0]->foto_file_id}/325/200"); ?>" alt="<?php echo $noticia->titulo; ?>" width="325" height="200">

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

                </section>

            <?php
                endforeach;
             ?>
