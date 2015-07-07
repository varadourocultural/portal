
            <article class="contact -profile">

                <section class="row">
                    <div class="grid-12">

                        <figure class="user-profile">
                            <img src="<?php echo ($usuario->avatar) ? site_url("/publico/thumb/{$usuario->avatar->id}/229/217") : '/img/user-no-photo.png'; ?>" alt="" width="229" height="217" >

                            <figcaption>
                            <?php echo $usuario->nome . ' ' . $usuario->sobrenome?>
                            </figcaption>
                        </figure>

                    </div>
                </section>

                <section class="row">

                    <div class="grid-8">

                        <img src="/img/pic-user-profile.jpg" alt="" width="676" height="700">

                    </div>
                    <div class="grid-4 sidebar">

                        <h1 class="side-heading -block">Seu perfil</h1>

                        <form class="contact-form" method="post" action="<?php echo site_url('/usuario/'.$usuario->username); ?>" role="form" enctype="multipart/form-data">

                        <?php if (validation_errors()):
                            echo validation_errors('<p class="feedback -error">', '</p>'); ?>
                        <?php elseif ($success): ?>
                            <p class="feedback -success">
                                Usuário atualizado com sucesso!
                            </p>
                        <?php endif; ?>

                            <p>
                                <label for="nome">Nome</label>
                                <?php echo form_input('nome', $usuario->nome, 'id="nome"'); ?>
                            </p>

                            <p>
                                <label for="sobrenome">Sobrenome</label>
                                <?php echo form_input('sobrenome', $usuario->sobrenome, 'id="sobrenome"'); ?>
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
                                <?php echo form_input('email', $usuario->email, 'id="email"'); ?>
                            </p>

                            <p>
                                <label for="senha">Senha</label>
                                <?php echo form_password('senha', '', 'id="senha"'); ?>

                            </p>

                            <p>
                                <label for="confirmacao_senha">Confirmação de Senha</label>
                                <?php echo form_password('confirmacao_senha', '', 'id="confirmacao_senha"'); ?>
                            </p>

                            <p>
                                <label for="agenda_publica">Agenda pública?</label>
                                <?php echo form_dropdown('agenda_publica', array('1' => 'Sim', '0' => 'Não'), $usuario->agenda_publica, 'id="agenda_publica"'); ?>
                            </p>

                            <button type="submit">Salvar</button>

                        </form>

                    </div>

                </section>

            </article>
