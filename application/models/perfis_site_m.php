<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfis_Site_m extends CI_Model {

    public function get($id)
    {
        return $this->db->get_where('perfis_site', array('id' => $id))->row();
    }

    public function get_by_usuario($usuario_id)
    {
        return $this->db
                ->join('usuarios', 'usuarios.id = perfis_site.usuario_id')
                ->where('usuario_id', $usuario_id)
                ->get('perfis_site')
                ->row();
    }

    public function get_by_username($username)
    {
        return $this->db
                ->where('username', $username)
                ->get('perfis_site')
                ->row();
    }

    public function get_by_email($email)
    {
        return $this->db
                ->join('usuarios', 'usuarios.id = perfis_site.usuario_id')
                ->where('email', $email)
                ->get('perfis_site')
                ->row();
    }

    public function get_all($limit = NULL, $start = NULL)
    {
        return $this->db
                ->join('usuarios', 'usuarios.id = perfis_site.usuario_id')
                ->order_by('username')
                ->get('perfis_site', $limit, $start)
                ->result();
    }

    public function insert($dados)
    {
        $r = $this->db->insert('perfis_site', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('perfis_site', $dados, array('id' => $id));
    }

    public function update_by_usuario($usuario_id, $dados)
    {
        return $this->db->update('perfis_site', $dados, array('usuario_id' => $usuario_id));
    }

    public function total()
    {
        return $this->db
                ->count_all('perfis_site');
    }

    public function delete($id)
    {
        return $this->db->delete('perfis_site', array('id' => $id));
    }

    public function delete_many($ids)
    {
        return $this->db
                ->where_in('id', $ids)
                ->delete('perfis_site');
    }

    public function validate($username, $password)
    {
        $u = $this->db
                ->join('usuarios', 'usuarios.id = perfis_site.usuario_id')
                ->where(array(
                    'nivel' => 0,
                    'email' => $username,
                    'senha' => sha1($password),
                    'ativo !=' => 0))
                ->get('perfis_site')
                ->row();

        if (! $u) {
            $u = $this->db
                ->join('usuarios', 'usuarios.id = perfis_site.usuario_id')
                ->where(array(
                    'nivel' => 0,
                    'username' => $username,
                    'senha' => sha1($password),
                    'ativo !=' => 0))
                ->get('perfis_site')
                ->row();
        }

        return $u;
    }

    public function checar_username($username)
    {
        $r = $this->db
                ->get_where('perfis_site', array('username' => $username))
                ->row();

        return $r;
    }

}
