<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arquivos_m extends CI_Model {

    public function get($id)
    {
        return $this->db->get_where('arquivos', array('id' => $id))->row();
    }

    public function get_all($limit = NULL, $start = NULL)
    {
        return $this->db
                ->order_by('nome_arquivo')
                ->get('arquivos', $limit, $start)
                ->result();
    }

    public function get_all_idade($idade, $temporarios = FALSE, $limit = NULL, $start = NULL)
    {
        $inicio = time() - $idade;

        $this->db
                ->order_by('data_criacao', 'desc')
                ->where('data_criacao <=', date('Y-m-d H:i:s', $inicio));

        if ($temporarios)
            $this->db->where('temp', TRUE);

        return $this->db->get('arquivos', $limit, $start)
                ->result();
    }

    public function insert($dados)
    {
        $r = $this->db->insert('arquivos', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('arquivos', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('arquivos');
    }

    public function delete($id)
    {
        return $this->db->delete('arquivos', array('id' => $id));
    }

    public function delete_many($ids)
    {
        return $this->db
                ->where_in('id', $ids)
                ->delete('arquivos');
    }

}
