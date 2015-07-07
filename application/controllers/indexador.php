<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indexador extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('eventos_m');
        $this->load->model('noticias_m');
        $this->load->model('espacos_culturais_m');
        $this->load->model('agentes_culturais_m');

        $this->load->helper('extra');

        $this->config->load('elasticsearch');
    }

    public function index()
    {
        if (! $this->input->is_cli_request())
            show_error('Acesso não autorizado', 403);

        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        try {
            $client->indices()->delete(array(
                'index' => 'varadouro',
            ));
        }
        catch (Exception $e) {
            echo "Indice não existe, será criado\n";
        }

        $client->indices()->create(array(
            'index' => 'varadouro',

            'body' => array(
                'mappings' => array(
                    'evento' => array(
                        'properties' => array(
                            'titulo' => array(
                                'type' => 'string',
                            ),

                            'slug' => array(
                                'type' => 'string',
                                'index' => 'not_analyzed',
                            ),

                            'descricao' => array(
                                'type' => 'string',
                            ),

                            'data' => array(
                                'type' => 'string',
                                'index' => 'not_analyzed',
                            ),
                        ),
                    ),

                    'noticia' => array(
                        'properties' => array(
                            'data' => array(
                                'type' => 'string',
                                'index' => 'not_analyzed',
                            ),

                            'titulo' => array(
                                'type' => 'string',
                            ),

                            'slug' => array(
                                'type' => 'string',
                                'index' => 'not_analyzed',
                            ),

                            'conteudo' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),

                    'espaco_cultural' => array(
                        'properties' => array(
                            'nome_espaco' => array(
                                'type' => 'string',
                            ),

                            'slug' => array(
                                'type' => 'string',
                                'index' => 'not_analyzed',
                            ),

                            'atividades_culturais' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),

                    'agente_cultural' => array(
                        'properties' => array(
                            'nome_responsavel' => array(
                                'type' => 'string',
                            ),

                            'slug' => array(
                                'type' => 'string',
                                'index' => 'not_analyzed',
                            ),

                            'atividades_culturais' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        ));

        echo "Indexando eventos...\n";

        $start = 0;
        $eventos = $this->eventos_m->get_all(128, $start);

        while ($eventos) {
            foreach ($eventos as $evento) {
                echo "Indexando \"{$evento->id} - {$evento->titulo}\"\n";

                $client->index(array(
                    'body' => array(
                        'titulo' => $evento->titulo,
                        'slug' => $evento->slug,
                        'descricao' => strip_tags_better($evento->descricao),
                        'data' => $evento->data,
                    ),

                    'index' => 'varadouro',

                    'type' => 'evento',

                    'id' => $evento->id,
                ));
            }

            $start += 128;
            $eventos = $this->eventos_m->get_all(128, $start);
        }

        echo "Indexando noticias...\n";

        $start = 0;
        $noticias = $this->noticias_m->get_all(128, $start);

        while ($noticias) {
            foreach ($noticias as $noticia) {
                echo "Indexando \"{$noticia->id} - {$noticia->titulo}\"\n";

                $client->index(array(
                    'body' => array(
                        'data' => $noticia->data,
                        'titulo' => $noticia->titulo,
                        'slug' => $noticia->slug,
                        'conteudo' => strip_tags_better($noticia->conteudo),
                    ),

                    'index' => 'varadouro',

                    'type' => 'noticia',

                    'id' => $noticia->id,
                ));
            }

            $start += 128;
            $noticias = $this->noticias_m->get_all(128, $start);
        }

        echo "Indexando espaços culturais...\n";

        $start = 0;
        $espacos = $this->espacos_culturais_m->get_all(128, $start);

        while ($espacos) {
            foreach ($espacos as $espaco) {
                echo "Indexando \"{$espaco->id} - {$espaco->nome_espaco}\"\n";

                $client->index(array(
                    'body' => array(
                        'nome_espaco' => $espaco->nome_espaco,
                        'slug' => $espaco->slug,
                        'atividades_culturais' => strip_tags_better($espaco->atividades_culturais),
                    ),

                    'index' => 'varadouro',

                    'type' => 'espaco_cultural',

                    'id' => $espaco->id,
                ));
            }

            $start += 128;
            $espacos = $this->espacos_culturais_m->get_all(128, $start);
        }

        echo "Indexando agentes culturais...\n";

        $start = 0;
        $agentes = $this->agentes_culturais_m->get_all(128, $start);

        while ($agentes) {
            foreach ($agentes as $agente) {
                echo "Indexando \"{$agente->id} - {$agente->nome_responsavel}\"\n";

                $client->index(array(
                    'body' => array(
                        'nome_responsavel' => $agente->nome_responsavel,
                        'slug' => $agente->slug,
                        'atividades_culturais' => strip_tags_better($agente->atividades_culturais),
                    ),

                    'index' => 'varadouro',

                    'type' => 'agente_cultural',

                    'id' => $agente->id,
                ));
            }

            $start += 128;
            $agentes = $this->agentes_culturais_m->get_all(128, $start);
        }
    }

}
