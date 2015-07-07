
                    <?php $num_itens = count($itens); ?>

                    <?php foreach ($itens as $item):
                            if($num_itens < 6 || ($item !== $itens[$num_itens - 1])):
                    ?>
                        <li>
                            <a href="<?php echo site_url($item->href); ?>" class="search-result">
                                Em <strong><?php switch($item->tipo) {
                                    case 'evento':
                                        echo 'eventos';
                                        break;
                                    case 'noticia':
                                        echo 'notícias';
                                        break;
                                    case 'espaco_cultural':
                                        echo 'espaços culturais';
                                        break;
                                    case 'agente_cultural':
                                        echo 'agentes culturais';
                                        break;
                                } ?></strong><?php switch($item->tipo) {
                                    case 'evento':
                                        $data = strtotime($item->data);
                                        echo ', '. date('d', $data) .' de '. $meses[intval(date('m', $data)) - 1] .' de '. date('Y', $data);
                                        break;
                                    case 'noticia':
                                        $data = strtotime($item->data);
                                        echo ', '. date('d', $data) .' de '. $meses[intval(date('m', $data)) - 1] .' de '. date('Y', $data);
                                        break;
                                } ?>
                                <span><?php echo $item->nome; ?></span>
                            </a>
                        </li>
                    <?php
                            endif;
                        endforeach;
                    ?>