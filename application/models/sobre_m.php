<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sobre_m extends CI_Model {

    public function get_first()
    {
        return $this->db
                ->order_by('id', 'desc')
                ->limit(1)
                ->get('sobre')
                ->row();
    }

    public function insert($dados)
    {
        $r = $this->db->insert('sobre', $dados);

        return $r;
    }

    public function delete_all()
    {
        return $this->db->empty_table('sobre');
    }

}
