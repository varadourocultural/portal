
            <article class="contact">

                <section class="row">
                    <div class="grid-8">

                        <img src="/img/pic-login.jpg" alt="" width="676" height="587">

                    </div>
                    <div class="grid-4 sidebar">

                        <h1 class="side-heading -block">Contato</h1>

                        <form class="contact-form" method="post" action="<?php echo site_url('/contato'); ?>" role="form">

                            <!-- feedback sempre presente, com inserção de classe e conteúdo
                            após submissão. -success ou -error pro feedback -->

                            <!-- <p class="feedback -success">
                                Mensagem enviada com sucesso!
                            </p>

                            <p class="feedback -error">
                                Todos os campos devem ser preenchidos!
                            </p>

                            <p class="feedback -error">
                                Email inválido!
                            </p>

                            <p class="feedback -error">
                                Problemas no servidor.<br>
                                Tente novamente mais tarde.
                            </p> -->

                            <p class="hp">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome">
                            </p>

                            <p>
                                <label for="name">Nome</label>
                                <?php echo form_input('name', @$dados_contato->name, 'id="name"'); ?>
                            </p>

                            <p>
                                <label for="email">E-mail</label>
                                <?php echo form_input('email', @$dados_contato->email, 'id="email"'); ?>
                            </p>

                            <p>
                                <label for="phone">Telefone</label>
                                <?php echo form_input('phone', @$dados_contato->phone, 'id="phone"'); ?>
                            </p>

                            <p>
                                <label for="message">Mensagem</label>
                                <?php echo form_textarea('message', html_entity_decode(@$dados_contato->message, NULL, 'UTF-8'), 'id="message"'); ?>
                            </p>

                            <button type="submit">Enviar</button>

                        </form>

                    </div>
                </section>

            </article>
