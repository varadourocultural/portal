
            <article class="contact -profile">

                <section class="row">
                    <div class="grid-8">

                        <img src="/img/pic-contact.jpg" alt="" width="676" height="700">

                    </div>
                    <div class="grid-4 sidebar">

                        <h1 class="side-heading -block">Registre-se</h1>

                        <form class="contact-form" method="post" action="<?php echo site_url('/registrar'); ?>" role="form" enctype="multipart/form-data">

                            <?php echo validation_errors('<p class="feedback -error">', '</p>'); ?>

                            <p class="hp">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome">
                            </p>

                            <p>
                                <label for="name">Nome</label>
                                <?php echo form_input('name', @$usuario->nome, 'id="name"'); ?>
                            </p>

                            <p>
                                <label for="sobrenome">Sobrenome</label>
                                <?php echo form_input('sobrenome', @$usuario->sobrenome, 'id="sobrenome"'); ?>
                            </p>

                            <!-- Bloquear API para troca de nome de usuário. Isso deve ser criado no registro de conta e nunca mais alterado. -->
                            <p>
                                <label for="username">Nome de usuário</label>
                                <?php echo form_input('username', @$usuario->username, 'id="username"'); ?>
                            </p>

                            <p>
                                <label for="avatar">Imagem</label>
                                <input type="file" id="avatar" name="avatar">
                            </p>

                            <?php
                                if ($usuario->avatar):
                            ?>
                                <input type="hidden" name="avatar_file_id" value="<?php echo $usuario->avatar->id; ?>">
                            <?php
                                endif;
                            ?>

                            <p>
                                <label for="email">E-mail</label>
                                <?php echo form_input('email', @$usuario->email, 'id="email"'); ?>
                            </p>

                            <p>
                                <label for="senha">Senha</label>
                                <?php echo form_password('senha', '', 'id="senha"'); ?>
                            </p>
                            <p>
                                <label for="confirmacao_senha">Confirmação de Senha</label>
                                <?php echo form_password('confirmacao_senha', '', 'id="confirmacao_senha"'); ?>
                            </p>

                            <button type="submit">Criar conta</button>

                        </form>

                    </div>
                </section>

            </article>
