
            <?php
                if(count($eventos) > 9) {
                    array_pop($eventos);
                }

                if($eventos):
                    $eventos = array_chunk($eventos, 3);

                    foreach ($eventos as $eventos_row):
            ?>

                <section class="row">

                <?php
                    foreach ($eventos_row as $evento):
                ?>

                    <article class="event-single grid-4">
                        <a href="<?php echo site_url('/evento'). '/'. urlencode($evento->slug); ?>">
                            <figure>

                            <?php
                                if ($evento->imagem_cover_file_id):
                            ?>

                                <img src="<?php echo site_url("/publico/thumb/{$evento->imagem_cover_file_id}/325/224"); ?>" alt="<?php echo $evento->titulo; ?>" width="325" height="224">

                            <?php
                                endif;
                            ?>

                                <figcaption>
                                    <span class="meta-info"><?php echo $evento->espaco->nome_espaco; ?></span>

                                    <h3><?php echo $evento->titulo ?></h3>

                                    <span class="meta-info"><?php echo $evento->informacoes_datas; ?></span>
                                    <span class="meta-info"><?php echo $evento->informacoes_horarios; ?></span>
                                    <span class="meta-info"><?php echo $evento->informacoes_valores; ?></span>
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

            <?php
                endif;
             ?>