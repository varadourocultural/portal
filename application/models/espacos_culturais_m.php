<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espacos_Culturais_m extends CI_Model {

    public function get($id, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->get_where('espacos_culturais', array('id' => $id, 'status' => 'publico'))->row();
        }
        else {
            $temp = $this->db
                ->get_where('espacos_culturais', array('id' => $id))->row();
        }

        return $temp;
    }

    public function get_by_slug($slug, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->where(array('slug' => $slug, 'status' => 'publico'))
                ->get('espacos_culturais')
                ->row();
        }
        else {
            $temp = $this->db
                ->where('slug', $slug)
                ->get('espacos_culturais')
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
                ->get('espacos_culturais', $limit, $start)
                ->result();
        }
        else {
            $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('espacos_culturais', $limit, $start)
                ->result();
        }

        return $temp;
    }

    public function get_all_by_area($atributo_id, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->select('espacos_culturais.*', 'espacos_culturais_atributos.espaco_cultural_id',  'espacos_culturais_atributos.atributo_id')
                ->from('espacos_culturais')
                ->join('espacos_culturais_atributos', 'espacos_culturais_atributos.espaco_cultural_id = espacos_culturais.id')
                ->where(
                        'status = "publico"
                        AND (atributo_id = '.$atributo_id.' OR area_atuacao_primaria = '.$atributo_id.')'

                )
                ->group_by('espacos_culturais.id')
                ->get()
                ->result();
        }
        else {
            $temp = $this->db
                ->select('espacos_culturais.*', 'espacos_culturais_atributos.espaco_cultural_id',  'espacos_culturais_atributos.atributo_id')
                ->from('espacos_culturais')
                ->join('espacos_culturais_atributos', 'espacos_culturais_atributos.espaco_cultural_id = espacos_culturais.id')
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
                ->get_where('espacos_culturais', array(
                        'tipo_espaco' => $atributo_id,
                        'status' => 'publico',
                ))
                ->result();
        }
        else {
            $temp = $this->db
                ->get_where('espacos_culturais', array(
                        'tipo_espaco' => $atributo_id,
                ))
                ->result();
        }

        return $temp;
    }

    public function get_all_with_img($limit = NULL, $start = NULL, $except = array(), $status = FALSE)
    {
        $this->load->model('espacos_culturais_fotos_m');

        if ($except) {
            if ($tatus) {
                $espacos = $this->db
                        ->order_by('ordem', 'desc')
                        ->where('status', 'publico')
                        ->where_not_in('id', $except)
                        ->get('espacos_culturais', $limit, $start)
                        ->result();
            }
            else {
                $espacos = $this->db
                        ->order_by('ordem', 'desc')
                        ->where_not_in('id', $except)
                        ->get('espacos_culturais', $limit, $start)
                        ->result();
            }
        }
        else {
            if ($status) {
                $espacos = $this->db
                        ->order_by('ordem', 'desc')
                        ->where('status', 'publico')
                        ->get('espacos_culturais', $limit, $start)
                        ->result();
            }
            else {
                $espacos = $this->db
                        ->order_by('ordem', 'desc')
                        ->get('espacos_culturais', $limit, $start)
                        ->result();
            }

        }

        foreach ($espacos as $espaco) {
            $espaco->fotos = $this->espacos_culturais_fotos_m->get_all_by_espaco_cultural($espaco->id);
        }

        return $espacos;
    }

    public function get_espaco_anterior($espaco_id, $status = FALSE)
    {
        $espaco = $this->get($espaco_id);

        if ($status) {
            $temp = $this->db
                ->where(array('ordem <' => $espaco->ordem, 'status' => 'publico'))
                ->order_by('ordem', 'desc')
                ->limit(1)
                ->get('espacos_culturais')
                ->row();
        }
        else {
            $temp = $this->db
                ->where(array('ordem <' => $espaco->ordem, 'status' => 'publico'))
                ->order_by('ordem', 'desc')
                ->limit(1)
                ->get('espacos_culturais')
                ->row();
        }

        return $temp;
    }

    public function get_espaco_proximo($espaco_id, $status = FALSE)
    {
        $espaco = $this->get($espaco_id);

        if ($status) {
            $temp = $this->db
                ->where(array('ordem >' => $espaco->ordem, 'status' => 'publico'))
                ->order_by('ordem', 'asc')
                ->limit(1)
                ->get('espacos_culturais')
                ->row();
        }
        else {
            $temp = $this->db
                ->where('ordem >', $espaco->ordem)
                ->order_by('ordem', 'asc')
                ->limit(1)
                ->get('espacos_culturais')
                ->row();
        }

        return $temp;
    }

    public function insert($dados)
    {
        $r = $this->db->insert('espacos_culturais', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('espacos_culturais', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('espacos_culturais');
    }

    public function delete($id)
    {
        $espaco_cultural = $this->get($id);

        $res = $this->db->delete('espacos_culturais', array('id' => $espaco_cultural->id));

        if ($res) {
            $this->load->library('arquivos');
            $this->load->model('arquivos_m');
            $this->load->model('espacos_culturais_fotos_m');
            $this->load->model('eventos_m');

            $fotos_old = $this->espacos_culturais_fotos_m->get_all_by_espaco_cultural($espaco_cultural->id);

            foreach ($fotos_old as $temp) {
                $res = $this->arquivos_m->update($temp->foto_file_id, array(
                    'temp' => TRUE,
                ));

                if (! $res)
                    return FALSE;
            }

            $res = $this->espacos_culturais_fotos_m->delete_all_by_espaco_cultural($espaco_cultural->id);

            if (! $res)
                return FALSE;

            $eventos_old = $this->eventos_m->get_all_by_espaco_cultural($espaco_cultural->id);

            foreach ($eventos_old as $temp) {
                $res = $this->eventos_m->delete($temp->id);

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
            ->update('espacos_culturais');
    }

    public function get_proxima_ordem()
    {
        $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('espacos_culturais', 1)
                ->row();

        return $temp ? $temp->ordem + 1 : 1;
    }

}
