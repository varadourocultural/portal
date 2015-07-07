<?php defined('BASEPATH') or exit('No direct script access allowed');

class Projetos_Imagens_m extends CI_Model
{

    public function insert($dados)
    {
        $r = $this->db->insert('projetos_imagens', $dados);

        return $r;
    }

    public function get_all()
    {
        return $this->db
                ->get('projetos_imagens')
                ->result();
    }

    public function delete_all()
    {
        return $this->db->empty_table('projetos_imagens');
    }

}
