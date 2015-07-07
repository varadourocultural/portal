<?php defined('BASEPATH') or exit('No direct script access allowed');

class Agentes_Culturais_Eventos_m extends CI_Model
{

    public function insert($evento_id, $dados)
    {
        $r = $this->db->insert('agentes_culturais_eventos', $dados + array(
                'evento_id' => $evento_id,
        ));

        return $r;
    }

    public function get_all_by_agente_cultural($agente_cultural_id, $limit = NULL, $start = NULL)
    {
        return $this->db
                ->get_where('agentes_culturais_eventos', array(
                        'agente_cultural_id' => $agente_cultural_id
                ), $limit, $start)
                ->result();
    }

    public function get_all_by_evento($evento_id)
    {
        return $this->db
                ->get_where('agentes_culturais_eventos', array(
                        'evento_id' => $evento_id,
                ))
                ->result();
    }

    public function get_first_children($agente_cultural_id, $slug)
    {
        $this->load->model('eventos_m');

        $atributo =  $this->eventos_m->get_slug($slug);

        return $this->db
                ->from('agentes_culturais_eventos')
                ->join('eventos', 'eventos.id = agentes_culturais_eventos.atributo_id')
                ->where(array(
                        'agente_cultural_id' => $agente_cultural_id,
                        'atributo_ascendente' => $atributo->id,
                ))->get()
                ->result();
    }

    public function delete_all_by_agente_cultural($agente_cultural_id)
    {
        return $this->db->delete('agentes_culturais_eventos', array(
                'agente_cultural_id' => $agente_cultural_id,
        ));
    }

    public function delete_all_by_evento($evento_id)
    {
        return $this->db->delete('agentes_culturais_eventos', array(
                'evento_id' => $evento_id,
        ));
    }

}
