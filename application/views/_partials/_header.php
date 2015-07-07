        <!--[if (lt IE 9) & (!IEMobile)]>
            <p class="browsehappy">Você está utilizando uma versão <strong>muito antiga</strong> de seu browser. Por favor atualize seu browser ou utilize um mais moderno para melhorar sua experiência.</p>
        <![endif]-->

        <header class="global-header" role="banner">

            <div class="row">

                <div class="grid-8">
                    <nav class="global-navigation" role="navigation">

                        <ul class="navigation-links" role="menubar">
                            <li role="menuitem">
                                <a href="<?php echo site_url('/quem-somos');?>" title="Sobre" tabindex="1">Sobre</a>
                            </li>
                            <li role="menuitem">
                                <a href="<?php echo site_url('/noticias');?>" title="Notícias" tabindex="2">Notícias</a>
                            </li>
                            <li role="menuitem">
                                <a href="<?php echo site_url('/projetos');?>" title="Projetos" tabindex="3">Projetos</a>
                            </li>
                            <li role="menuitem">
                                <a href="<?php echo site_url('/agenda');?>" title="Agenda cultural" tabindex="4">Agenda cultural</a>
                            </li>
                            <li role="menuitem">
                                <a href="<?php echo site_url('/espacos-agentes');?>" title="Espaços e agentes" tabindex="5">Espaços e agentes</a>
                            </li>
                            <li role="menuitem">
                                <a href="<?php echo site_url('/contato');?>" title="Contato" tabindex="6">Contato</a>
                            </li>
                        </ul>

                    </nav>

                <?php if(! $u = $this->session->userdata('usuario_site')): ?>
                    <div class="user-login">
                        <div class="user-div">
                        <span>Efetue seu <a href="<?php echo site_url('/login');?>">login</a> ou <a href="<?php echo site_url('/registrar');?>">registre-se</a></span>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="user-login">
                        <div class="user-div">
                        <span>Olá, <a href="#"><?php echo $u->nome . ' ' . $u->sobrenome; ?></a>.</span>
                        </div>
                        <div class="user-actions">
                            <a href="<?php echo site_url('/usuario/'.$u->username);?>">Seu Perfil</a> | <a href="<?php echo site_url('/usuario/'.$u->username.'/agenda');?>">Ver Agenda</a> | <a href="<?php echo site_url('/admin');?>">Colabore</a> | <a href="<?php echo site_url('/logout');?>">Logout</a>
                        </div>
                    </div>

                <?php endif;?>


                </div>

                <div class="grid-4 side-header">

                    <a href="<?php echo site_url('/');?>" class="logo" title="Logotipo Varadouro Cultural" tabindex="0">Varadouro Cultural</a>

                    <ul class="social-links">
                        <li>
                            <a href="http://www.facebook.com/varadourocultural" class="social-link -facebook" tabindex="7">Facebook</a>
                        </li>
                        <li>
                            <a href="http://www.instagram.com/varadourocultural" class="social-link -instagram" tabindex="8">Instagram</a>
                        </li>
                        <li>
                            <a href="http://www.twitter.com/varadourocult" class="social-link -twitter" tabindex="9">Twitter</a>
                        </li>
                        <li>
                            <a href="http://www.youtube.com/user/varadourocultural" class="social-link -youtube" tabindex="11">YouTube</a>
                        </li>
                    </ul>

                    <form class="search-form" method="get" action="<?php echo site_url('/busca'); ?>" role="search">
                        <label for="search-query" class="hidden">Buscar</label>
                        <input type="search" name="q" class="search-input" id="search-query" placeholder="Buscar" role="input" tabindex="12">
                        <input type="submit" class="search-button" value="Search" role="button" tabindex="13">
                    </form>
                </div>

            </div>

        </header>
