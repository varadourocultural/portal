
            <article class="whatweare">

                <section class="row">
                    <div class="grid-8">

                        <img src="/img/pic-whoweare.jpg" alt="" width="676" height="587">

                    </div>
                    <div class="grid-4 sidebar">

                        <h1 class="side-heading -block">O que somos?</h1>
                        <?php echo $sobre->o_que_somos; ?>

                    </div>
                </section>

                <section class="row actions">
                    <div class="grid-12">
                        <h2>Ações e realizações</h2>

                        <div class="row">

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($sobre->acoes_coluna_1); ?>

                            </div>

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($sobre->acoes_coluna_2); ?>

                            </div>

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($sobre->acoes_coluna_3); ?>

                            </div>

                        </div>
                    </div>
                </section>

            </article>
