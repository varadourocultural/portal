<?php defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios_Agenda_m extends CI_Model
{

    public function insert($usuario_id, $evento_id)
    {
        $r = $this->db->insert('usuarios_agenda', array(
                'usuario_id' => $usuario_id,
                'evento_id' => $evento_id,
        ));

        return $r;
    }

    public function get_all_by_usuario($usuario_id, $limit = NULL, $start = NULL)
    {
        $this->load->model('eventos_m');

        if ($limit) {
            $temp =  $this->db
                    ->from('usuarios_agenda')
                    ->join('eventos', 'eventos.id = usuarios_agenda.evento_id')
                    ->where(array(
                            'usuario_id' => $usuario_id,
                    ))->limit($limit, $start)
                    ->get()
                    ->result();
        }
        else {
            $temp =  $this->db
                    ->from('usuarios_agenda')
                    ->join('eventos', 'eventos.id = usuarios_agenda.evento_id')
                    ->where(array(
                            'usuario_id' => $usuario_id,
                    ))->get()
                    ->result();
        }

        return $temp;
    }

    public function get_all_by_evento($evento_id)
    {
        return $this->db
                ->get_where('usuarios_agenda', array(
                        'evento_id' => $evento_id,
                ))
                ->result();
    }

    public function delete_all_by_usuario($usuario_id)
    {
        return $this->db->delete('usuarios_agenda', array(
                'usuario_id' => $usuario_id,
        ));
    }

    public function delete_all_by_evento($evento_id)
    {
        return $this->db->delete('usuarios_agenda', array(
                'evento_id' => $evento_id,
        ));
    }

    public function search($usuario_id, $evento_id)
    {
        $result = $this->db
                ->get_where('usuarios_agenda', array(
                        'usuario_id' => $usuario_id,
                        'evento_id' => $evento_id,
                ))
                ->result();

        if($result) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function delete($usuario_id, $evento_id)
    {
        return $this->db->delete('usuarios_agenda', array(
                'usuario_id' => $usuario_id,
                'evento_id' => $evento_id,
            ));
    }

}
