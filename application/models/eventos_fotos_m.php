<?php defined('BASEPATH') or exit('No direct script access allowed');

class Eventos_Fotos_m extends CI_Model
{

    public function insert($evento_id, $dados)
    {
        $r = $this->db->insert('eventos_fotos', $dados + array(
                'evento_id' => $evento_id,
        ));

        return $r;
    }

    public function get_all_by_evento($evento_id)
    {
        return $this->db
                ->get_where('eventos_fotos', array(
                        'evento_id' => $evento_id,
                ))
                ->result();
    }

    public function delete_all_by_evento($evento_id)
    {
        return $this->db->delete('eventos_fotos', array(
                'evento_id' => $evento_id,
        ));
    }

}
