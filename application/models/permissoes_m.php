<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissoes_m extends CI_Model {

    public function get($id)
    {
        return $this->db
                ->get_where('permissoes', array('id' => $id))->row();
    }

    public function get_all($limit = NULL, $start = NULL)
    {
        return $this->db
                ->order_by('ordem')
                ->get('permissoes', $limit, $start)
                ->result();
    }

    public function get_all_nivel($nivel_minimo)
    {
        return $this->db
                ->where('nivel <=', $nivel_minimo)
                ->order_by('ordem')
                ->order_by('id')
                ->get('permissoes')
                ->result();
    }

    public function checar_permissao($nivel, $acao)
    {
        $permissoes = $this->get_all_nivel($nivel);

        foreach ($permissoes as $perm) {
            if ($perm->acao == $acao) {
                return $perm->permitir;
            }
            elseif (strpos($perm->acao, '.*') !== FALSE) {
                list($acao_wildcard, $_) = explode('.*', $perm->acao, 2);

                if (strpos($acao, $acao_wildcard . '.') === 0) {
                    return $perm->permitir;
                }
            }
        }

        return FALSE;
    }

    public function insert($dados)
    {
        $r = $this->db->insert('permissoes', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('permissoes', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('permissoes');
    }

    public function delete($id)
    {
        $permissao = $this->get($id);

        $res = $this->db->delete('permissoes', array('id' => $permissao->id));

        if ($res) {
            $this->load->library('arquivos');
            $this->load->model('arquivos_m');

            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function atualiza_ordem($depois_de)
    {
        $this->db
            ->where('ordem>=', $depois_de, FALSE)
            ->set('ordem', 'ordem + 1', FALSE)
            ->update('permissoes');
    }

    public function get_proxima_ordem()
    {
        $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('permissoes', 1)
                ->row();

        return $temp ? $temp->ordem + 1 : 1;
    }

}
