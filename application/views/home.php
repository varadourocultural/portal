
            <div class="row">
                <section class="grid-8 map-container">

                <?php $this->load->view('_partials/_home_map', array('espacos' => $espacos, 'agentes' => $agentes, 'areas_primarias' => $areas_primarias,
                                        'tipos_espacos' => $tipos_espacos)); ?>

                </section>
                <section class="grid-4 sidebar -home">

                    <h2 class="side-heading -block">Agenda<br> cultural</h2>

                <?php if($eventos_destaque[0]): ?>
                    <article class="short-event -home-featured -sidebar">

                    <?php
                    if($eventos_destaque[0])
                        if ($eventos_destaque[0]->imagem_cover_file_id):
                    ?>

                        <a href="<?php echo site_url('/evento'). '/'. urlencode($eventos_destaque[0]->slug); ?>" style="background-image: url(<?php echo site_url("/publico/thumb/{$eventos_destaque[0]->imagem_cover_file_id}/800/600"); ?>)">

                    <?php
                        else:
                    ?>

                        <a href="<?php echo site_url('/evento'). '/'. urlencode($eventos_destaque[0]->slug); ?>" style="background-image: none">

                    <?php
                        endif;
                    ?>

                            <div class="meta-info-container">
                                <span class="meta-info"><?php echo $eventos_destaque[0]->espaco->nome_espaco; ?></span>

                                <h3><?php echo $eventos_destaque[0]->titulo; ?></h3>

                                <span class="meta-info"><?php echo $eventos_destaque[0]->informacoes_datas; ?></span>
                                <span class="meta-info"><?php echo $eventos_destaque[0]->informacoes_horarios; ?></span>
                                <span class="meta-info"><?php echo $eventos_destaque[0]->informacoes_valores; ?></span>
                            </div>

                        </a>

                    </article>
                <?php endif; ?>

                </section>
            </div>

            <div class="row">

                <div class="grid-6 no-padding-right">
            <?php if(isset($eventos_destaque[1])): ?>
                    <article class="short-event -home-featured -left">

                    <?php
                        if ($eventos_destaque[1]->imagem_cover_file_id):
                    ?>

                        <a href="<?php echo site_url('/evento'). '/'. urlencode($eventos_destaque[1]->slug); ?>" style="background-image: url(<?php echo site_url("/publico/thumb/{$eventos_destaque[1]->imagem_cover_file_id}/514/600"); ?>)">

                    <?php
                        else:
                    ?>

                        <a href="<?php echo site_url('/evento'). '/'. urlencode($eventos_destaque[1]->slug); ?>" style="background-image: none">

                    <?php
                        endif;
                    ?>

                            <div class="meta-info-container">
                                <span class="meta-info"><?php echo $eventos_destaque[1]->espaco->nome_espaco; ?></span>

                                <h3><?php echo $eventos_destaque[1]->titulo; ?></h3>

                                <span class="meta-info"><?php echo $eventos_destaque[1]->informacoes_datas;?></span>
                                <span class="meta-info"><?php echo $eventos_destaque[1]->informacoes_horarios;?></span>
                                <span class="meta-info"><?php echo $eventos_destaque[1]->informacoes_valores;?></span>
                            </div>

                        </a>

                    </article>
            <?php endif; ?>
                </div>
                <div class="grid-6 no-padding-left">

                    <div class="agenda-links">

                        <a href="" class="agenda-link -home">
                            Faça sua programação cultural personalizada
                            <small>Experimente!</small>
                        </a>

                        <a href="<?php echo site_url('/agenda');?>" class="agenda-link -login">
                            Ver agenda completa
                        </a>

                    </div>

                <?php if(isset($eventos_destaque[2])): ?>
                    <article class="short-event -home-featured -right">

                    <?php
                        if ($eventos_destaque[2]->imagem_cover_file_id):
                    ?>

                        <a href="<?php echo site_url('/evento'). '/'. urlencode($eventos_destaque[2]->slug); ?>" style="background-image: url(<?php echo site_url("/publico/thumb/{$eventos_destaque[2]->imagem_cover_file_id}/800/600"); ?>)">

                    <?php
                        else:
                    ?>

                        <a href="<?php echo site_url('/evento'). '/'. urlencode($eventos_destaque[2]->slug); ?>" style="background-image: none">

                    <?php
                        endif;
                    ?>

                            <div class="meta-info-container">
                                <span class="meta-info"><?php echo $eventos_destaque[2]->espaco->nome_espaco; ?></span>

                                <h3><?php echo $eventos_destaque[2]->titulo; ?></h3>

                                <span class="meta-info"><?php echo $eventos_destaque[2]->informacoes_datas;?></span>
                                <span class="meta-info"><?php echo $eventos_destaque[2]->informacoes_horarios;?></span>
                                <span class="meta-info"><?php echo $eventos_destaque[2]->informacoes_valores;?></span>
                            </div>

                        </a>

                    </article>
                <?php endif; ?>
                </div>
            </div>

            <section class="home-news">
                <header>
                    <h2>Notícias</h2>

                    <a href="<?php echo site_url('/noticias'); ?>">Ver todas as notícias</a>
                </header>

            <?php
                $noticias = array_chunk($noticias, 3);

                foreach ($noticias as $noticias_row):
            ?>
                <div class="row">

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

                </div>
            <?php
                endforeach;
            ?>

            </section>
