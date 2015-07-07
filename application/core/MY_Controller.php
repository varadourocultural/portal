<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $usuario = NULL;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->usuario = $this->session->userdata('usuario');

        if (! $this->usuario) {
            $this->session->set_flashdata('error',
                    'Você precisa se autenticar para acessar este recurso.');

            redirect('/admin/login');
        }
        else {
            $this->load->vars(array('usuario' => $this->usuario));
        }
    }

}
