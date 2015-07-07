
            <article class="contact">

                <section class="row">
                    <div class="grid-8">

                        <img src="/img/pic-login.jpg" alt="" width="676" height="587">

                    </div>
                    <div class="grid-4 sidebar">

                        <h1 class="side-heading -block">Login</h1>

                        <form class="contact-form" method="post" action="<?php echo site_url('/login'); ?>" role="form">

                            <p class="feedback <?php if (@$erro) echo '-error'; ?>">
                                <?php echo @$erro; ?>
                            </p>

                            <p class="hp">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome">
                            </p>

                            <p>
                                <label for="username">E-mail ou Nome de Usuário</label>
                                <?php echo form_input('username', @$usuario->email, 'id="username"'); ?>
                            </p>

                            <p>
                                <label for="senha">Senha</label>
                                <?php echo form_password('senha', '', 'id="senha"'); ?>
                            </p>

                            <button type="submit">Enviar</button>

                        </form>

                        <h1 class="side-heading -block">Esqueceu?</h1>

                        <form class="contact-form" method="post" action="<?php echo site_url('/recuperar'); ?>" role="form">

                            <p class="feedback <?php echo @$error ? '-error' : '-success'; ?>">
                                <?php echo @$feedback; ?>
                            </p>

                            <p>
                                <label for="email">E-mail</label>
                                <?php echo form_input('email', @$usuario->email, 'id="email"'); ?>
                            </p>

                            <button type="submit">Recuperar</button>

                        </form>

                    </div>
                </section>

            </article>
