<?php defined('BASEPATH') or exit('No direct script access allowed');

class Espacos_Culturais_Fotos_m extends CI_Model
{

    public function insert($espaco_cultural_id, $dados)
    {
        $r = $this->db->insert('espacos_culturais_fotos', $dados + array(
                'espaco_cultural_id' => $espaco_cultural_id,
        ));

        return $r;
    }

    public function get_all_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db
                ->get_where('espacos_culturais_fotos', array(
                        'espaco_cultural_id' => $espaco_cultural_id,
                ))
                ->result();
    }

    public function delete_all_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db->delete('espacos_culturais_fotos', array(
                'espaco_cultural_id' => $espaco_cultural_id,
        ));
    }

}
