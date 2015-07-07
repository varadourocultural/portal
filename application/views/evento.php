
            <article class="event">

                <section class="row">
                    <div class="grid-8 event-flyer-container" id="space-description">
                        <div class="event-flyer-sub-container">
                            <a class="event-flyer" href="#link-para-imagem-tamanho-original" title="<?php echo $evento->titulo; ?>">

                        <?php
                            if($evento->imagem_cover_file_id):
                        ?>

                                <img src="<?php echo site_url("/publico/thumb/{$evento->imagem_cover_file_id}/776/1500"); ?>" alt="<?php echo $evento->titulo; ?>" width="676" height="500">

                        <?php
                            endif;
                        ?>

                            </a>
                        </div>
                    </div>
                    <div class="grid-4 sidebar" id="space-location">

                        <h2 class="side-heading -inline">Agenda Cultural</h2>

                        <nav class="single-pagination">
                            <a <?php echo ($evento->anterior) ? 'href="'.site_url("/evento/".$evento->anterior->slug).'"' : ''; ?> class="<?php echo ($evento->anterior) ? 'previous' :'disabled' ?>">Anterior</a>
                            <a <?php echo ($evento->proximo) ? 'href="'.site_url("/evento/".$evento->proximo->slug).'"' : ''; ?> class="<?php echo ($evento->proximo) ? 'next' :'disabled' ?>">Próximo</a>
                        </nav>

                    <?php if($u = $this->session->userdata('usuario_site') && ! ($evento->adicionado)): ?>
                        <a href="<?php echo site_url('/adiciona-evento/'.$evento->id); ?>" class="add-to-agenda">
                            Adicionar à <br/>minha programação
                        </a>
                    <?php endif; ?>

                        <div class="events-map" id="map" data-lat="<?php echo $evento->espaco->latitude; ?>" data-long="<?php echo $evento->espaco->longitude; ?>"></div>

                        <div class="location-meta">

                            <span class="location-title"><?php echo $evento->espaco->nome_espaco; ?></span>

                            <span class="location-address"><?php echo $evento->espaco->endereco; ?></span>

                            <span class="location-phone"><?php echo $evento->espaco->telefone_comercial; ?></span>

                            <a href="#" class="location-email"><?php echo $evento->espaco->email; ?></a>

                            <a href="<?php echo 'http://' . $evento->espaco->site; ?>" class="location-url"><?php echo $evento->espaco->site; ?></a>

                        <?php
                            $horarios = $evento->espaco->horario;
                            $num_dias = count($evento->espaco->horario);
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

                                        if ($horario == $evento->espaco->horario[$num_dias - 1])
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
                            ?>
                            </span>

                        </div>

                        <table class="event-datetime-price">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="meta-info"><?php echo nl2br ($evento->informacoes_datas);?></span>
                                    </td>
                                    <td>
                                        <span class="meta-info"><?php echo nl2br ($evento->informacoes_horarios);?></span>
                                    </td>
                                    <td>
                                        <span class="meta-info"><?php echo nl2br ($evento->informacoes_valores);?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </section>

                <section class="row">
                    <div class="grid-12">
                        <header class="event-title">
                            <h1><?php echo $evento->titulo; ?></h1>

                        <?php if($agentes): ?>
                            <?php $num_agentes = count($agentes); ?>

                                <h2>

                            <?php foreach ($agentes as $agente) {
                                if ($agente == $agentes[$num_agentes - 1]) {
                                    echo $agente->nome_responsavel;
                                }
                                else {
                                    echo $agente->nome_responsavel . ' / ';
                                }
                            }
                             ?>

                                </h2>
                        <?php endif; ?>

                        </header>

                        <div class="event-description">

                            <div class="row">

                                <div class="grid-5">

                                    <div class="location-content">
                                        <div class="addthis_toolbox addthis_default_style ">
                                            <a class="addthis_button_preferred_1"></a>
                                            <a class="addthis_button_preferred_2"></a>
                                            <a class="addthis_button_preferred_3"></a>
                                            <a class="addthis_button_preferred_4"></a>
                                            <a class="addthis_button_compact"></a>
                                            <a class="addthis_counter addthis_bubble_style"></a>
                                        </div>

                                        <p><?php echo $evento->descricao; ?></p>
                                    </div>

                                </div>

                                <div class="grid-7">
                                    <div class="swiper-container">
                                      <div class="swiper-wrapper">

                                    <?php foreach ($evento->fotos as $foto): ?>
                                          <div class="swiper-slide">
                                            <img src="<?php echo site_url("/publico/thumb/{$foto->foto_file_id}/588/428"); ?>" alt="<?php echo $evento->titulo; ?>" width="588" height="428">
                                          </div>
                                    <?php endforeach;?>

                                      </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </section>

            </article>
