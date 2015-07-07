
                    <div class="map-filters-container" id="map-filters-container">

                        <span class="filter-title">Mapa cultural do centro de João Pessoa</span>

                        <div class="filters-area">

                            <span class="filters-area-title" id="filters-area-title">Mostrar filtros</span>

                            <form method="get" action="<?php echo site_url('/map_home_ajax'); ?>" class="map-filter-form" id="map-filter-form">

                                <div class="iScrollContainer">

                                    <h4>Área de atuação Cultural</h4>

                                <?php foreach ($areas_primarias as $area_primaria): ?>
                                    <input class="prettyCheckable" type="checkbox" name="a[]" value="<?php echo $area_primaria->id; ?>" data-label="<?php echo $area_primaria->nome; ?>">
                                <?php endforeach; ?>


                                    <h4>Tipo do Espaço Cultural</h4>

                                <?php foreach ($tipos_espacos as $tipo): ?>
                                    <input class="prettyCheckable" type="checkbox" name="t[]" value="<?php echo $tipo->id; ?>" data-label="<?php echo $tipo->nome; ?>">
                                <?php endforeach; ?>


                                </div>


                            </form>

                        </div>

                    </div>

                    <div class="map" id="map">

                    </div>