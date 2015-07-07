
            <article class="agent">

                <section class="row">
                    <div class="grid-8">

                    <?php
                        if($agente->fotos):
                    ?>

                        <img src="<?php echo site_url("/publico/thumb/{$agente->fotos[0]->foto_file_id}/676/500"); ?>" alt="<?php echo $agente->nome_responsavel; ?>">

                    <?php
                        endif;
                    ?>

                    </div>
                    <div class="grid-4 sidebar">

                        <h2 class="side-heading -inline">Agentes</h2>

                        <nav class="single-pagination">

                            <a <?php echo ($agente->anterior) ? 'href="'.site_url("/agentes/".$agente->anterior->slug).'"' : ''; ?> class="<?php echo ($agente->anterior) ? 'previous' :'disabled' ?>">Anterior</a>

                            <a <?php echo ($agente->proximo) ? 'href="'.site_url("/agentes/".$agente->proximo->slug).'"' : ''; ?> class="<?php echo ($agente->proximo) ? 'next' :'disabled' ?>">Próximo</a>
                        </nav>

                        <div class="location-meta">

                            <span class="location-category"><i><?php echo $agente->area_primaria->sigla; ?></i><?php echo $agente->area_primaria->nome; ?></span>

                            <span class="location-address"><?php echo $agente->endereco; ?></span>

                            <span class="location-phone"><?php echo $agente->celular; ?></span>

                            <a href="#" class="location-email"><?php echo $agente->email; ?></a>

                            <a href="<?php echo 'http://' . $agente->site; ?>" class="location-url"><?php echo $agente->site; ?></a>

                        <?php
                            $horarios = $agente->horario;
                            $num_dias = count($agente->horario);
                        ?>

                            <span class="location-times">
                            <?php
                                echo 'Horário: ';

                                foreach ($horarios as $horario)
                                {
                                    if ($horario->horario_abertura && $horario->horario_fechamento)
                                    {
                                        echo utf8_encode(substr(utf8_decode($dias_semana[$horario->dia_semana]), 0, 3)).' - ';

                                        $horario_abertura = explode(':', $horario->horario_abertura);
                                        $horario_fechamento = explode(':', $horario->horario_fechamento);

                                        if ($horario == $agente->horario[$num_dias - 1])
                                        {
                                            echo $horario_abertura[0] .'H' . $horario_abertura[1]
                                            . '-' . $horario_fechamento[0] . 'H' . $horario_fechamento[1];
                                        } else
                                        {
                                            echo $horario_abertura[0] .'H' . $horario_abertura[1]
                                            . '-' . $horario_fechamento[0] . 'H' . $horario_fechamento[1] . '; ';
                                        }
                                    }
                                }

                                if ($agente->fechado_almoco == 1) {
                                    echo 'Fechado para almoço(12H às 14H)';
                                }
                            ?>
                            </span>

                        </div>

                    </div>
                </section>

                <section class="row">
                    <div class="grid-12">

                        <div class="location-info">

                            <header class="location-heading">
                                <h1><?php echo $agente->nome_responsavel; ?></h1>

                                <span class="filters">
                                <?php
                                    $num_filtros = count($agente->filtros);

                                    echo 'Filtros: ';
                                    foreach ($agente->filtros as $filtro)
                                    {
                                        if($filtro == $agente->filtros[$num_filtros - 1])
                                        {
                                            echo $filtro->nome;
                                        } else
                                        {
                                            echo $filtro->nome.', ';
                                        }
                                    }
                                ?>
                                </span>

                                <div class="addthis_toolbox addthis_default_style ">
                                    <a class="addthis_button_preferred_1"></a>
                                    <a class="addthis_button_preferred_2"></a>
                                    <a class="addthis_button_preferred_3"></a>
                                    <a class="addthis_button_preferred_4"></a>
                                    <a class="addthis_button_compact"></a>
                                    <a class="addthis_counter addthis_bubble_style"></a>
                                </div>

                            </header>

                            <div class="location-content">

                               <?php echo $agente->atividades_culturais; ?>

                            </div>

                        </div>
                    </div>
                </section>

                <aside class="location-related">

                <?php if ($agente->eventos_relacionados): ?>

                    <h3 class="related-title">Eventos relacionados</h3>

                    <div class="row">

                    <?php
                        foreach ($agente->eventos_relacionados as $evento):
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
                                        <span class="meta-info"><?php echo $evento->nome_espaco; ?></span>

                                        <h3><?php echo $evento->titulo; ?></h3>

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
                    </div>

                <?php endif; ?>

                <?php if ($noticias): ?>

                    <h3 class="related-title">Notícias relacionadas</h3>

                    <div class="row">

                    <?php
                        foreach ($noticias as $noticia):
                            $data = strtotime($noticia->data);
                    ?>

                        <article class="news-single grid-4">
                            <a href="<?php echo site_url('/noticias'). '/'. urlencode($noticia->slug); ?>">
                                <figure>

                                <?php
                                    if ($noticia->fotos):
                                ?>

                                    <img src="<?php echo site_url("/publico/image/{$noticia->fotos[0]->foto_file_id}"); ?>" alt="<?php echo $noticia->titulo; ?>" width="325" height="200">

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

                <?php endif; ?>

                </aside>

            </article>
