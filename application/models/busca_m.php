<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Busca_m extends CI_Model {

    public function buscar($q, $tipos, $limit = NULL, $start = NULL)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $types = '';
        $campos = array();

        $ultimo = count($tipos);
        $ultimo_tipo = $tipos[$ultimo - 1];

        $client = new Elasticsearch\Client($client_params);

        foreach ($tipos as $tipo) {
            if($tipo == $ultimo_tipo) {
                $types .= $tipo;
                $campos = array_merge($campos, $this->get_campos($tipo));
            }
            else {
                $types .= $tipo.',';
                $campos = array_merge($campos, $this->get_campos($tipo));
            }
        }

        $params['index'] = 'varadouro';
        $params['type'] = $types;

        $filter = array();

        $query = array();

        if (strlen($q) > 0) {
            $query['multi_match'] = array(
                'query' => $q,
                'fields' => $campos,
            );
        }
        else {
            $query['match_all'] = array();
        }

        $params['body']['from'] = $start;
        $params['body']['size'] = $limit;

        $params['body']['highlight'] = array(
            'pre_tags' => array('<span class="highlight">'),
            'post_tags' => array('</span>'),

            'fields' => array(
                'nome_responsavel' => array('type' => 'plain'),
                'atividades_culturais' => array('type' => 'plain'),
            ),
        );

        $params['body']['query']['filtered'] = array(
            'filter' => $filter,
            'query'  => $query,
        );

        $results = $client->search($params);

        return $results;
    }

    private function get_campos($tipo) {
        $campos = array();

        if ($tipo == 'evento') {
            $campos = array_merge_recursive($campos, array('evento.titulo', 'evento.descricao'));
        }
        else if ($tipo == 'noticia') {
            $campos = array_merge_recursive($campos, array('noticia.titulo', 'noticia.autor', 'noticia.conteudo'));
        }
        else if ($tipo == 'espaco_cultural') {
            $campos = array_merge_recursive($campos, array('espaco_cultural.nome_espaco', 'espaco_cultural.atividades_culturais'));
        }
        else if ($tipo == 'agente_cultural') {
            $campos = array_merge_recursive($campos, array('agente_cultural.nome_responsavel', 'agente_cultural.atividades_culturais'));
        }

        return $campos;
    }

}