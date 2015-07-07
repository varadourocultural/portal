<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->helper('permissoes');

        $this->breadcrumbs = array(
            array('Principal', site_url('/admin')),
        );

        $this->navbar = array(
        );

        $this->layout->set_layout('admin/layout_default');
    }

    public function index()
    {

        $dados = array(
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/admin/index', $dados);
    }

}
