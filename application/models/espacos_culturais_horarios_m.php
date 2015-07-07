<?php defined('BASEPATH') or exit('No direct script access allowed');

class Espacos_Culturais_Horarios_m extends CI_Model
{

    public function insert($espaco_cultural_id, $dados)
    {
        $r = $this->db->insert('espacos_culturais_horarios', $dados + array(
                'espaco_cultural_id' => $espaco_cultural_id,
        ));

        return $r;
    }

    public function get_all_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db
                ->order_by('dia_semana')
                ->get_where('espacos_culturais_horarios', array(
                        'espaco_cultural_id' => $espaco_cultural_id,
                ))
                ->result();
    }

    public function delete_all_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db->delete('espacos_culturais_horarios', array(
                'espaco_cultural_id' => $espaco_cultural_id,
        ));
    }

}
