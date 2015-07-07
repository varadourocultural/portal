<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_m extends CI_Model {

    public function get($id)
    {
        return $this->db->get_where('usuarios', array('id' => $id))->row();
    }

    public function get_by_name($nome)
    {
        return $this->db
                ->where('nome', $nome)
                ->get('usuarios')
                ->row();
    }

    public function get_all($limit = NULL, $start = NULL)
    {
        return $this->db
                ->order_by('nome')
                ->get('usuarios', $limit, $start)
                ->result();
    }

    public function insert($dados)
    {
        $r = $this->db->insert('usuarios', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('usuarios', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('usuarios');
    }

    public function delete($id)
    {
        return $this->db->delete('usuarios', array('id' => $id));
    }

    public function delete_many($ids)
    {
        return $this->db
                ->where_in('id', $ids)
                ->delete('usuarios');
    }

    public function validate($username, $password, $nivel_minimo = 0)
    {
        return $this->db
                ->get_where('usuarios', array(
                    'nivel >=' => $nivel_minimo,
                    'email' => $username,
                    'senha' => sha1($password),
                    'ativo !=' => 0)
                )->row();
    }

    public function checar_email($email, $id_usuario = NULL)
    {
        if($id_usuario) {
            $r = $this->db
                    ->where(array( 'id !=' => $id_usuario, 'email' => $email))
                    ->get('usuarios')
                    ->row();
        }
        else {
            $r = $this->db
                    ->get_where('usuarios', array('email' => $email))
                    ->row();
        }

        return $r;
    }

}
