
            <header class="projects-heading">
                <h1>Projetos</h1>

                <a href="#<?php echo $projeto->projeto_1; ?>" data-scroller><?php echo $projeto->projeto_1; ?></a>
                <a href="#<?php echo $projeto->projeto_2; ?>" data-scroller><?php echo $projeto->projeto_2; ?></a>
            </header>

            <div class="row projects-container">

                <div class="grid-12">

                    <article class="project-single" id="<?php echo $projeto->projeto_1; ?>">

                        <header>
                            <h2><?php echo $projeto->projeto_1; ?></h2>

                            <img src="/img/pic-header-projects.jpg" alt="Imagem Mapeamento Urbano" width="352" height="135">
                        </header>

                        <div class="row">

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($projeto->proj_1_col_1); ?>

                            </div>

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($projeto->proj_1_col_2); ?>

                            </div>

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($projeto->proj_1_col_3); ?>

                            </div>

                        </div>

                    </article>

                </div>

                <div class="grid-12">

                    <article class="project-single" id="<?php echo $projeto->projeto_2; ?>">

                        <header>
                            <h2><?php echo $projeto->projeto_2; ?></h2>

                            <img src="/img/pic-header-circuit.jpg" alt="Imagem Circuito cultural" width="352" height="135">
                        </header>

                        <div class="row">

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($projeto->proj_2_col_1); ?>

                            </div>

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($projeto->proj_2_col_2); ?>

                            </div>

                            <div class="grid-4">

                                <?php echo htmlspecialchars_decode($projeto->proj_2_col_3); ?>

                            </div>

                        </div>

                    </article>

                </div>

                <div class="grid-12">
                    <div class="swiper-container">
                      <div class="swiper-wrapper">

                    <?php if ($imagens): ?>

                        <?php foreach ($imagens as $imagem): ?>

                          <div class="swiper-slide">
                            <img src="<?php echo site_url('/publico/thumb/'.$imagem->imagem_file_id.'/1028/428'); ?>" alt="" width="1028" height="428">
                          </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                      </div>
                    </div>
                </div>

            </div>
