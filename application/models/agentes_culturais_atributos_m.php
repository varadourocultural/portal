<?php defined('BASEPATH') or exit('No direct script access allowed');

class Agentes_Culturais_Atributos_m extends CI_Model
{

    public function insert($agente_cultural_id, $dados)
    {
        $r = $this->db->insert('agentes_culturais_atributos', $dados + array(
                'agente_cultural_id' => $agente_cultural_id,
        ));

        return $r;
    }

    public function get_all_by_agente_cultural($agente_cultural_id)
    {
        return $this->db
                ->get_where('agentes_culturais_atributos', array(
                        'agente_cultural_id' => $agente_cultural_id,
                ))
                ->result();
    }

    public function get_first_children($agente_cultural_id, $slug, $limit = NULL)
    {
        $this->load->model('atributos_m');

        $atributo =  $this->atributos_m->get_slug($slug);

        return $this->db
                ->join('atributos', 'atributos.id = agentes_culturais_atributos.atributo_id')
                ->where(array(
                        'agente_cultural_id' => $agente_cultural_id,
                        'atributo_ascendente' => $atributo->id,
                ))->get('agentes_culturais_atributos', $limit)
                ->result();
    }

    public function delete_all_by_agente_cultural($agente_cultural_id)
    {
        return $this->db->delete('agentes_culturais_atributos', array(
                'agente_cultural_id' => $agente_cultural_id,
        ));
    }

}
