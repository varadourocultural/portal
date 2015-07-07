
                <?php

                    if(count($espacos) > 4) {
                        array_pop($espacos);
                    }

                    $espacos = array_chunk($espacos, 2);

                    foreach ($espacos as $espacos_row):
                ?>

                    <div class="row">

                    <?php
                        foreach ($espacos_row as $espaco):
                    ?>

                        <article class="location-listing grid-6">
                            <a href="<?php echo site_url('/espacos'). '/'. urlencode($espaco->slug); ?>">
                                <figure>

                                <?php
                                    if ($espaco->fotos):
                                ?>

                                    <img src="<?php echo site_url("/publico/thumb/{$espaco->fotos[0]->foto_file_id}/325/224"); ?>" alt="<?php echo $espaco->nome_espaco; ?>" width="325" height="224">

                                <?php
                                    endif;
                                ?>

                                    <figcaption>
                                        <i class="location-map-icon"><?php echo $espaco->area_primaria->sigla; ?></i>
                                        <span class="meta-info"><?php echo $espaco->area_primaria->nome; ?></span>

                                        <h3><?php echo $espaco->nome_espaco; ?></h3>

                                        <p><?php echo character_limiter(strip_tags_better($espaco->atividades_culturais), 200); ?></p>

                                        <span class="filters">
                                        <?php echo 'Filtros: '.character_limiter(strip_tags_better($espaco->filtros_str), 100); ?>
                                        </span>
                                    </figcaption>
                                </figure>
                            </a>
                        </article>

                    <?php
                        endforeach;
                    ?>

                    </div>

                <?php
                    endforeach;
                ?>
