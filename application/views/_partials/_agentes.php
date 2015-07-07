

                <?php

                    if(count($agentes) > 2) {
                        array_pop($agentes);
                    }

                    $agentes = array_chunk($agentes, 1);

                    foreach ($agentes as $agentes_row):
                ?>

                    <div class="row">

                    <?php
                        foreach ($agentes_row as $agente):
                    ?>

                        <article class="location-listing grid-12">
                            <a href="<?php echo site_url('/agentes'). '/'. urlencode($agente->slug); ?>">
                                <figure>

                                <?php
                                    if ($agente->fotos):
                                ?>
                                    <img src="<?php echo site_url("/publico/thumb/{$agente->fotos[0]->foto_file_id}/325/224"); ?>" alt="<?php echo $agente->nome_responsavel; ?>" width="325" height="224">

                                <?php
                                    endif;
                                ?>

                                    <figcaption>
                                        <i class="location-map-icon"><?php echo $agente->area_primaria->sigla; ?></i>
                                        <span class="meta-info"><?php echo $agente->area_primaria->nome; ?></span>

                                        <h3><?php echo $agente->nome_responsavel; ?></h3>

                                        <p><?php echo character_limiter(strip_tags_better($agente->atividades_culturais), 200); ?></p>

                                        <span class="filters">
                                        <?php echo 'Filtros: '.character_limiter(strip_tags_better($agente->filtros_str), 100); ?>
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