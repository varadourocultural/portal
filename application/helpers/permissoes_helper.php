<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function checar_permissao($acao, $redir = '/admin')
{
    $CI =& get_instance();

    $CI->load->model('permissoes_m');
    $CI->load->helper('url');

    $usuario = $CI->session->userdata('usuario');
    $ok = $CI->permissoes_m->checar_permissao($usuario->nivel, $acao);

    if ($redir && ! $ok) {
        $CI->session->set_flashdata('error', 'Permissão negada. Contate o administrador.');
        redirect($redir);
    }
    else {
        return $ok;
    }
}
