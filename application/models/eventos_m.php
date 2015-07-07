<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eventos_m extends CI_Model {

    public function get($id, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->get_where('eventos', array('id' => $id, 'status' => 'publico'))->row();
        }
        else {
            $temp = $this->db
                ->get_where('eventos', array('id' => $id))->row();
        }

        return $temp;
    }

    public function get_by_slug($slug, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->where(array('slug' => $slug, 'status' => 'publico'))
                ->get('eventos')
                ->row();
        }
        else {
            $temp = $this->db
                ->where('slug', $slug)
                ->get('eventos')
                ->row();
        }

        return $temp;
    }

    public function get_all_with_img($limit = NULL, $start = NULL, $except = array(), $status = FALSE)
    {
        $this->load->model('eventos_fotos_m');

        if($except) {
            if ($tatus) {
                $eventos = $this->db
                        ->order_by('data', 'desc')
                        ->where('status', 'publico')
                        ->where_not_in('id', $except)
                        ->get('eventos', $limit, $start)
                        ->result();
            }
            else {
                $eventos = $this->db
                        ->order_by('data', 'desc')
                        ->where_not_in('id', $except)
                        ->get('eventos', $limit, $start)
                        ->result();
            }
        }
        else {
            if ($tatus) {
                $eventos = $this->db
                        ->order_by('data', 'desc')
                        ->where('status', 'publico')
                        ->get('eventos', $limit, $start)
                        ->result();
            }
            else {
                $eventos = $this->db
                        ->order_by('data', 'desc')
                        ->get('eventos', $limit, $start)
                        ->result();
            }
        }

        foreach ($eventos as $evento) {
            $evento->fotos = $this->eventos_fotos_m->get_all_by_evento($evento->id);
        }

        return $eventos;
    }

    public function get_all($limit = NULL, $start = NULL, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->order_by('data', 'desc')
                ->where('status', 'publico')
                ->get('eventos', $limit, $start)
                ->result();
        }
        else {
            $temp = $this->db
                ->order_by('data', 'desc')
                ->get('eventos', $limit, $start)
                ->result();
        }
        return $temp;
    }

    public function get_all_by_date($ano, $mes, $dia, $limit = NULL, $start = NULL, $status = FALSE)
    {
        $data = mktime(0, 0, 0, $mes, $dia, $ano);
        $data = date('Ymd', $data);

        if ($status) {
            $temp = $this->db
                ->order_by('data', 'desc')
                ->where(array('data' => $data, 'status' => 'publico'))
                ->get('eventos', $limit, $start)
                ->result();
        }
        else {
            $temp = $this->db
                ->order_by('data', 'desc')
                ->where('data', $data)
                ->get('eventos', $limit, $start)
                ->result();
        }

        return $temp;
    }

    public function get_all_by_espaco_cultural($espaco_cultural_id, $limit = NULL, $start = NULL, $status = FALSE)
    {
        if ($status) {
            $temp = $this->db
                ->where(array('espaco_cultural' => $espaco_cultural_id, 'status' => 'publico'))
                ->order_by('data', 'desc')
                ->get('eventos', $limit, $start)
                ->result();
        }
        else {
            $temp = $this->db
                ->where('espaco_cultural', $espaco_cultural_id)
                ->order_by('data', 'desc')
                ->get('eventos', $limit, $start)
                ->result();
        }

        return $temp;
    }

        public function get_all_by_agente_cultural($agente_cultural_id, $limit = NULL, $status = FALSE)
    {
        $this->load->model('agentes_culturais_eventos_m');

        if ($status) {
            $temp = $this->db
                ->from('agentes_culturais_eventos')
                ->join('eventos', 'eventos.id = agentes_culturais_eventos.evento_id')
                ->where(array(
                        'agente_cultural_id' => $agente_cultural_id,
                        'status' => 'publico',
                    ))
                ->limit($limit)
                ->get()
                ->result();
        }
        else {
            $temp = $this->db
                ->from('agentes_culturais_eventos')
                ->join('eventos', 'eventos.id = agentes_culturais_eventos.evento_id')
                ->where(array(
                        'agente_cultural_id' => $agente_cultural_id,
                    ))
                ->limit($limit)
                ->get()
                ->result();
        }

        return $temp;
    }

    public function get_evento_anterior($evento_id, $status = FALSE)
    {
        $evento = $this->get($evento_id);

        if ($status) {
            $temp = $this->db
                ->where(array('id <' => $evento->id, 'status' => 'publico'))
                ->order_by('id', 'desc')
                ->limit(1)
                ->get('eventos')
                ->row();
        }
        else {
            $temp = $this->db
                ->where('id <', $evento->id)
                ->order_by('id', 'desc')
                ->limit(1)
                ->get('eventos')
                ->row();
        }

        return $temp;
    }

    public function get_evento_proximo($evento_id, $status = FALSE)
    {
        $evento = $this->get($evento_id);

        if ($status) {
            $temp = $this->db
                ->where(array('id >' => $evento->id, 'status' => 'publico'))
                ->order_by('id', 'asc')
                ->limit(1)
                ->get('eventos')
                ->row();
        }
        else {
            $temp = $this->db
                ->where('id >', $evento->id)
                ->order_by('id', 'asc')
                ->limit(1)
                ->get('eventos')
                ->row();
        }

        return $temp;
    }

    public function insert($dados)
    {
        $r = $this->db->insert('eventos', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('eventos', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('eventos');
    }

    public function total_by_espaco_cultural($espaco_cultural_id)
    {
        return $this->db
                ->where('espaco_cultural', $espaco_cultural_id)
                ->from('eventos')
                ->count_all_results();
    }

    public function delete($id)
    {
        $evento = $this->get($id);

        $res = $this->db->delete('eventos', array('id' => $evento->id));

        if ($res) {
            $this->load->library('arquivos');
            $this->load->model('arquivos_m');

            if ($evento->imagem_cover_file_id) {
                $res = $this->arquivos_m->update($evento->imagem_cover_file_id, array(
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

}
