<?php defined('BASEPATH') or exit('No direct script access allowed');

class Noticias_Fotos_m extends CI_Model
{

    public function insert($noticia_id, $dados)
    {
        $r = $this->db->insert('noticias_fotos', $dados + array(
                'noticia_id' => $noticia_id,
        ));

        return $r;
    }

    public function get_all_by_noticia($noticia_id)
    {
        return $this->db
                ->get_where('noticias_fotos', array(
                        'noticia_id' => $noticia_id,
                ))
                ->result();
    }

    public function delete_all_by_noticia($noticia_id)
    {
        return $this->db->delete('noticias_fotos', array(
                'noticia_id' => $noticia_id,
        ));
    }

}
