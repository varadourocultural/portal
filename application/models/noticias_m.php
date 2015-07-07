<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias_m extends CI_Model {

    public function get($id)
    {
        return $this->db
                ->get_where('noticias', array('id' => $id))->row();
    }

    public function get_by_slug($slug)
    {
        return $this->db
                ->where('slug', $slug)
                ->get('noticias')
                ->row();
    }

    public function get_all($limit = NULL, $start = NULL)
    {
        return $this->db
                ->order_by('ordem', 'desc')
                ->get('noticias', $limit, $start)
                ->result();
    }

    public function get_all_with_img($limit = NULL, $start = NULL, $except = array())
    {
        $this->load->model('noticias_fotos_m');

        if($except) {
            $noticias = $this->db
                    ->order_by('ordem', 'desc')
                    ->where_not_in('id', $except)
                    ->get('noticias', $limit, $start)
                    ->result();
        }
        else {
            $noticias = $this->db
                    ->order_by('ordem', 'desc')
                    ->get('noticias', $limit, $start)
                    ->result();

        }

        foreach ($noticias as $noticia) {
            $noticia->fotos = $this->noticias_fotos_m->get_all_by_noticia($noticia->id);
        }

        return $noticias;
    }

    public function insert($dados)
    {
        $r = $this->db->insert('noticias', $dados);

        if ($r !== FALSE)
            return $this->db->insert_id();

        else
            return $r;
    }

    public function update($id, $dados)
    {
        return $this->db->update('noticias', $dados, array('id' => $id));
    }

    public function total()
    {
        return $this->db
                ->count_all('noticias');
    }

    public function delete($id)
    {
        $noticia = $this->get($id);

        $res = $this->db->delete('noticias', array('id' => $noticia->id));

        if ($res) {
            $this->load->library('arquivos');
            $this->load->model('arquivos_m');
            $this->load->model('noticias_fotos_m');

            $fotos_old = $this->noticias_fotos_m->get_all_by_noticia($noticia->id);

            foreach ($fotos_old as $temp) {
                $res = $this->arquivos_m->update($temp->foto_file_id, array(
                    'temp' => TRUE,
                ));

                if (! $res)
                    return FALSE;
            }

            $res = $this->noticias_fotos_m->delete_all_by_noticia($noticia->id);

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
            ->update('noticias');
    }

    public function get_proxima_ordem()
    {
        $temp = $this->db
                ->order_by('ordem', 'desc')
                ->get('noticias', 1)
                ->row();

        return $temp ? $temp->ordem + 1 : 1;
    }

}
