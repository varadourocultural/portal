<?php defined('BASEPATH') or exit('No direct script access allowed');

class Agentes_Culturais_Fotos_m extends CI_Model
{

    public function insert($agente_cultural_id, $dados)
    {
        $r = $this->db->insert('agentes_culturais_fotos', $dados + array(
                'agente_cultural_id' => $agente_cultural_id,
        ));

        return $r;
    }

    public function get_all_by_agente_cultural($agente_cultural_id)
    {
        return $this->db
                ->get_where('agentes_culturais_fotos', array(
                        'agente_cultural_id' => $agente_cultural_id,
                ))
                ->result();
    }

    public function delete_all_by_agente_cultural($agente_cultural_id)
    {
        return $this->db->delete('agentes_culturais_fotos', array(
                'agente_cultural_id' => $agente_cultural_id,
        ));
    }

}
