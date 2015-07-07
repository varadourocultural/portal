
            <article class="events -profile">

                <section class="row">
                    <div class="grid-12">

                        <figure class="user-profile">
                            <img src="<?php echo ($usuario->avatar) ? site_url("/publico/thumb/{$usuario->avatar->id}/229/217") : '/img/user-no-photo.png'; ?>" alt="">

                            <figcaption>
                                <?php echo $usuario->nome . ' ' . $usuario->sobrenome; ?>
                            </figcaption>
                        </figure>

                    </div>
                    <div class="grid-12">

                    <?php
                        $u = $this->session->userdata('usuario_site');
                        if($u && $u->username == $usuario->username):
                    ?>
                        <h1>Seus eventos</h1>
                    <?php else: ?>
                        <h1>Eventos dele</h1>
                    <?php endif; ?>

                        <a href="<?php echo site_url('/usuario/'.$usuario->username.'/agenda-pdf'); ?>" class="print-agenda">Exportar Agenda</a>
                    </div>
                </section>

                <div class="usuario-agenda-result">
                <?php $this->load->view('_partials/_eventos_agenda', array('eventos' => $eventos, 'u' => $u)); ?>
                </div>

                <div class="row">
                    <div class="grid-12">
                    <?php if($has_more): ?>
                        <a href="<?php echo site_url('/usuario_agenda_ajax/'.$usuario->username); ?>" class="load-more -blue" data-controller="usuario-agenda" data-start="<?php echo $start; ?>">Abrir mais eventos</a>
                    <?php endif; ?>
                    </div>
                </div>

            </article>
