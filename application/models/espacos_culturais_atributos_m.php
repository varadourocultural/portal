<?php defined('BASEPATH') or exit('No direct script access allowed');

class Espacos_Culturais_Atributos_m extends CI_Model
{

    public function insert($espaco_cultural_id, $dados)
    {
        $r = $this->db->insert('espacos_culturais_atributos', $dados + array(
                'espaco_cultural_id' => $espaco_cultural_id,
        ));

        return $r;
    }

    public function get_all_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db
                ->get_where('espacos_culturais_atributos', array(
                        'espaco_cultural_id' => $espaco_cultural_id,
                ))
                ->result();
    }

    public function get_first_children($espaco_cultural_id, $slug, $limit = NULL)
    {
        $this->load->model('atributos_m');

        $atributo =  $this->atributos_m->get_slug($slug);

        return $this->db
                ->join('atributos', 'atributos.id = espacos_culturais_atributos.atributo_id')
                ->where(array(
                        'espaco_cultural_id' => $espaco_cultural_id,
                        'atributo_ascendente' => $atributo->id,
                ))
                ->get('espacos_culturais_atributos', $limit)
                ->result();
    }

    public function delete_all_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db->delete('espacos_culturais_atributos', array(
                'espaco_cultural_id' => $espaco_cultural_id,
        ));
    }

}
