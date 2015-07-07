<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agentes_Culturais_m extends CI_Model {

    public function get($id, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->get_where('agentes_culturais', array('id' => $id, 'status' => 'publico'))->row();
        }
        else {
            $temp = $this->db
                ->get_where('agentes_culturais', array('id' => $id))->row();
        }
        return $temp;
    }

    public function get_by_slug($slug, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->where(array('slug' => $slug, 'status' => 'publico'))
                ->get('agentes_culturais')
                ->row();
        }
        else {
            $temp = $this->db
                ->where('slug', $slug)
                ->get('agentes_culturais')
                ->row();
        }

        return $temp;
    }

    public function get_all($limit = NULL, $start = NULL, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->order_by('ordem', 'desc')
                ->where('status', 'publico')
                ->get('agentes_culturais', $limit, $start)
                ->result();
        }
        else {
            $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('agentes_culturais', $limit, $start)
                ->result();
        }

        return $temp;
    }

    public function get_all_by_area($atributo_id, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->select('agentes_culturais.*', 'agentes_culturais_atributos.agente_cultural_id',  'agentes_culturais_atributos.atributo_id')
                ->from('agentes_culturais')
                ->join('agentes_culturais_atributos', 'agentes_culturais_atributos.agente_cultural_id = agentes_culturais.id')
                ->where(
                        'status = "publico"
                        AND (atributo_id = '.$atributo_id.' OR area_atuacao_primaria = '.$atributo_id.')'

                )
                ->group_by('agentes_culturais.id')
                ->get()
                ->result();
        }
        else {
            $temp = $this->db
                ->select('agentes_culturais.*', 'agentes_culturais_atributos.agente_cultural_id',  'agentes_culturais_atributos.atributo_id')
                ->from('agentes_culturais')
                ->join('agentes_culturais_atributos', 'agentes_culturais_atributos.agente_cultural_id = agentes_culturais.id')
                ->where(
                        '(atributo_id = '.$atributo_id.' OR area_atuacao_primaria = '.$atributo_id.')'

                )->get()
                ->result();
        }

        return $temp;
    }

    public function get_all_by_tipo($atributo_id, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->get_where('agentes_culturais', array(
                        'tipo_espaco' => $atributo_id,
                        'status' => 'publico',
                ))
                ->result();
        }
        else {
            $temp = $this->db
                ->get_where('agentes_culturais', array(
                        'tipo_espaco' => $atributo_id,
                ))
                ->result();
        }

        return $temp;
    }

    public function get_all_with_img($limit = NULL, $start = NULL, $except = array(), $status = FALSE)
    {
        $this->load->model('agentes_culturais_fotos_m');

        if($except) {
            if ($status) {
                $agentes = $this->db
                    ->order_by('ordem', 'desc')
                    ->where('status', 'publico')
                    ->where_not_in('id', $except)
                    ->get('agentes_culturais', $limit, $start)
                    ->result();
            }
            else {
                $agentes = $this->db
                    ->order_by('ordem', 'desc')
                    ->where_not_in('id', $except)
                    ->get('agentes_culturais', $limit, $start)
                    ->result();
            }

        }
        else {
            if ($status) {
                $agentes = $this->db
                        ->order_by('ordem', 'desc')
                        ->where('status', 'publico')
                        ->get('agentes_culturais', $limit, $start)
                        ->result();
            }
            else {
                $agentes = $this->db
                        ->order_by('ordem', 'desc')
                        ->get('agentes_culturais', $limit, $start)
                        ->result();
            }

        }

        foreach ($agentes as $agente) {
            $agente->fotos = $this->agentes_culturais_fotos_m->get_all_by_agente_cultural($agente->id);
        }

        return $agentes;
    }

    public function get_by_name($nome, $limit = NULL, $start = NULL)
    {
        return $this->db
                ->like('nome_responsavel', $nome, 'after')
                ->get('agentes_culturais', $limit, $start)
                ->result();
    }

    public function get_agente_anterior($agente_id)
    {
        $agente = $this->get($agente_id);

        return $this->db
                ->where(array('ordem <' => $agente->ordem, 'status' => 'publico'))
                ->order_by('ordem', 'desc')
                ->limit(1)
                ->get('agentes_culturais')
                ->row();
    }

    public function get_agente_proximo($agente_id)
    {
        $agente = $this->get($agente_id);

        return $this->db
                ->where(array('ordem >' => $agente->ordem, 'status' => 'publico'))
                ->order_by('ordem', 'asc')
                ->limit(1)
                ->get('agentes_culturais')
                ->row();
    }

    public function insert($dados)
    {
        $r = $this->db->insert('agentes_culturais', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('agentes_culturais', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('agentes_culturais');
    }

    public function delete($id)
    {
        $agente_cultural = $this->get($id);

        $res = $this->db->delete('agentes_culturais', array('id' => $agente_cultural->id));

        if ($res) {
            $this->load->library('arquivos');
            $this->load->model('arquivos_m');
            $this->load->model('agentes_culturais_fotos_m');

            $fotos_old = $this->agentes_culturais_fotos_m->get_all_by_agente_cultural($agente_cultural->id);

            foreach ($fotos_old as $temp) {
                $res = $this->arquivos_m->update($temp->foto_file_id, array(
                    'temp' => TRUE,
                ));

                if (! $res)
                    return FALSE;
            }

            $res = $this->agentes_culturais_fotos_m->delete_all_by_agente_cultural($agente_cultural->id);

            if (! $res)
                return FALSE;

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
            ->update('agentes_culturais');
    }

    public function get_proxima_ordem()
    {
        $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('agentes_culturais', 1)
                ->row();

        return $temp ? $temp->ordem + 1 : 1;
    }

}
