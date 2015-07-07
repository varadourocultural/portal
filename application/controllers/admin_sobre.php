<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Sobre extends MY_Controller {

    private $validation_rules = array(
        array(
            'field' => 'id',
            'label' => 'Código',
            'rules' => 'trim',
        ),

        array(
            'field' => 'o_que_somos',
            'label' => 'O que somos?',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'acoes_coluna_1',
            'label' => 'Ações e Realizações Coluna 1',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'acoes_coluna_2',
            'label' => 'Ações e Realizações Coluna 2',
            'rules' => 'trim',
        ),

        array(
            'field' => 'acoes_coluna_3',
            'label' => 'Ações e Realizações Coluna 3',
            'rules' => 'trim',
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->lang->load('form_validation');
        $this->load->library('arquivos');
        $this->load->model('arquivos_m');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('permissoes');
        $this->load->model('sobre_m');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');
    }

    public function index()
    {
        checar_permissao('sobre.gerenciar');

        $sobre = $this->sobre_m
                ->get_first();

        if (! $sobre) {
            $sobre = new stdClass();

            $sobre->o_que_somos = '';
            $sobre->acoes_coluna_1 = '';
            $sobre->acoes_coluna_2 = '';
            $sobre->acoes_coluna_3 = '';
        }
        else {
        }

        $this->init_breadcrumbs();
        $this->init_navbar('index');

        $dados = array(
            'sobre' => $sobre,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'id' => NULL,
                    'o_que_somos' => $this->input->post('o_que_somos'),
                    'acoes_coluna_1' => $this->input->post('acoes_coluna_1'),
                    'acoes_coluna_2' => $this->input->post('acoes_coluna_2'),
                    'acoes_coluna_3' => $this->input->post('acoes_coluna_3'),
                );

                $this->db->trans_start();

                $this->sobre_m->delete_all();
                $this->sobre_m->insert($data);
            }

            $this->db->trans_complete();

            $this->session->set_flashdata('success', 'Sobre atualizados com sucesso.');
            redirect('admin/sobre');
        }

        $this->layout->view('admin/sobre/index', $dados);
    }

    public function upload_ajax()
    {
        $erros = '';

        if (is_uploaded_file(@$_FILES['arquivo']['tmp_name'])) {
            $temp = $this->arquivos->adicionar(array(
                'nome' => $_FILES['arquivo']['name'],
                'caminho' => $_FILES['arquivo']['tmp_name'],
                'tipo_mime' => $_FILES['arquivo']['type'],
            ), TRUE);

            if ($temp !== FALSE)
                $_POST['arquivo_id'] = $temp[0];
        }
        else {
            $erros = '<p>' . sprintf($this->lang->line('required'), 'Arquivo')
                    . '</p>';
        }

        $arquivo_id = $this->input->post('arquivo_id')
                ? $this->input->post('arquivo_id')
                : NULL;

        echo json_encode(array(
            'arquivo_id' => $arquivo_id,
            'url' => $arquivo_id ? site_url('/publico/image/' . $arquivo_id) : NULL,
            'erros' => $erros,
        ));
    }

    private function listar_arquivos($campo)
    {
        $arquivos = array();
        $temp = (array) array_filter($this->input->post("{$campo}_file_id"));

        foreach ($temp as $fid) {
            $a = new stdClass();
            $a->{"{$campo}_file_id"} = $fid;
            $arquivos[] = $a;
        }

        return $arquivos;
    }

    private function init_breadcrumbs()
    {
        $this->breadcrumbs[] = array('Sobre',
                site_url("admin/sobre"));
    }

    private function init_navbar($method)
    {
    }

}
