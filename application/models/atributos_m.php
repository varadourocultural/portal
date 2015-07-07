<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atributos_m extends CI_Model {

    public function get($id, $filhos = FALSE)
    {
        $atributo = $this->db
                ->get_where('atributos', array('id' => $id))->row();

        if ($filhos && $atributo) {
            $atributo->itens = $this->get_all_filhos($atributo->id);
        }

        return $atributo;
    }

    public function get_slug($slug, $filhos = FALSE)
    {
        $atributo = $this->db
                ->get_where('atributos', array('slug' => $slug))->row();

        if ($filhos && $atributo) {
            $atributo->itens = $this->get_all_filhos($atributo->id);
        }

        return $atributo;
    }

    public function get_all($filhos = TRUE, $limit = NULL, $start = NULL)
    {
        if (! $filhos) {
            $this->db
                    ->where('NOT atributo_ascendente', NULL, FALSE);
        }

        return $this->db
                ->order_by('ordem')
                ->get('atributos', $limit, $start)
                ->result();
    }

    public function get_all_filhos($ascendente, $limit = NULL, $start = NULL)
    {
        return $this->db
                ->where('atributo_ascendente', $ascendente)
                ->order_by('ordem')
                ->get('atributos', $limit, $start)
                ->result();
    }

    public function get_all_ascendentes($atributo_id, $somente_ids = FALSE, $limite = 10)
    {
        $temp = $this->get($atributo_id);

        if (! $temp) {
            return array();
        }

        $ascendentes = array($temp);
        $ascendente = $this->get($temp->atributo_ascendente);

        while ($ascendente) {
            $ascendentes[] = $somente_ids ? $ascendente->id : $ascendente;
            $ascendente = $this->get($ascendente->atributo_ascendente);
        }

        return $ascendentes;
    }


    public function get_first_ascendente($atributo_id)
    {
        $ascendentes = $this->get_all_ascendentes($atributo_id);

        if (! $ascendentes) {
            return array();
        }

        $first_ascendente = end($ascendentes);

        return $first_ascendente;
    }


    public function insert($dados)
    {
        $r = $this->db->insert('atributos', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('atributos', $dados, array('id' => $id));
    }

    public function total($filhos = TRUE)
    {
        if (! $filhos) {
            $this->db
                    ->where('NOT atributo_ascendente', NULL, FALSE);
        }

        return $this->db
                ->from('atributos')
                ->count_all_results();
    }

    public function total_filhos($ascendente)
    {
        return $this->db
                ->where('atributo_ascendente', $ascendente)
                ->from('atributos')
                ->count_all_results();
    }

    public function delete($id)
    {
        $atributo = $this->get($id);

        $res = $this->db->delete('atributos', array('id' => $atributo->id));

        if ($res) {
            $this->load->library('arquivos');
            $this->load->model('arquivos_m');

            if ($atributo->icone_file_id) {
                $res = $this->arquivos_m->update($atributo->icone_file_id, array(
                    'temp' => TRUE,
                ));

                if (! $res)
                    return FALSE;
            }

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
            ->update('atributos');
    }

    public function get_proxima_ordem()
    {
        $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('atributos', 1)
                ->row();

        return $temp ? $temp->ordem + 1 : 1;
    }

}
