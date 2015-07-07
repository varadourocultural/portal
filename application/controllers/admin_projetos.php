<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Projetos extends MY_Controller {

    private $validation_rules = array(
        array(
            'field' => 'id',
            'label' => 'Código',
            'rules' => 'trim',
        ),

        array(
            'field' => 'projeto_1',
            'label' => 'Título',
            'rules' => 'trim',
        ),

        array(
            'field' => 'proj_1_col_1',
            'label' => 'Coluna 1',
            'rules' => 'trim',
        ),

        array(
            'field' => 'proj_1_col_2',
            'label' => 'Coluna 2',
            'rules' => 'trim',
        ),

        array(
            'field' => 'proj_1_col_3',
            'label' => 'Coluna 3',
            'rules' => 'trim',
        ),

        array(
            'field' => 'projeto_2',
            'label' => 'Título',
            'rules' => 'trim',
        ),

        array(
            'field' => 'proj_2_col_1',
            'label' => 'Coluna 1',
            'rules' => 'trim',
        ),

        array(
            'field' => 'proj_2_col_2',
            'label' => 'Coluna 2',
            'rules' => 'trim',
        ),

        array(
            'field' => 'proj_2_col_3',
            'label' => 'Coluna 3',
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

        $this->load->model('projetos_imagens_m');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('permissoes');
        $this->load->model('projetos_m');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');
    }

    public function index()
    {
        checar_permissao('projetos.gerenciar');

        $projetos = $this->projetos_m
                ->get_first();

        if (! $projetos) {
            $projetos = new stdClass();

            $projetos->projeto_1 = '';
            $projetos->proj_1_col_1 = '';
            $projetos->proj_1_col_2 = '';
            $projetos->proj_1_col_3 = '';
            $projetos->projeto_2 = '';
            $projetos->proj_2_col_1 = '';
            $projetos->proj_2_col_2 = '';
            $projetos->proj_2_col_3 = '';

            $projetos->imagens = array();
        }
        else {

            $projetos->imagens = $this->projetos_imagens_m->get_all();
        }

        $this->init_breadcrumbs();
        $this->init_navbar('index');

        $dados = array(
            'projetos' => $projetos,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'id' => NULL,
                    'projeto_1' => $this->input->post('projeto_1'),
                    'proj_1_col_1' => $this->input->post('proj_1_col_1'),
                    'proj_1_col_2' => $this->input->post('proj_1_col_2'),
                    'proj_1_col_3' => $this->input->post('proj_1_col_3'),
                    'projeto_2' => $this->input->post('projeto_2'),
                    'proj_2_col_1' => $this->input->post('proj_2_col_1'),
                    'proj_2_col_2' => $this->input->post('proj_2_col_2'),
                    'proj_2_col_3' => $this->input->post('proj_2_col_3'),
                );

                $this->db->trans_start();

                $this->projetos_m->delete_all();
                $this->projetos_m->insert($data);
            }

            $imagens_old = $projetos->imagens;
            $projetos->imagens = $this->listar_arquivos('imagem');

            foreach ($imagens_old as $temp) {
                $this->arquivos_m->update($temp->imagem_file_id, array(
                    'temp' => TRUE,
                ));
            }

            $this->projetos_imagens_m->delete_all();

            foreach ($projetos->imagens as $temp) {
                $this->projetos_imagens_m->insert(array(
                    'imagem_file_id' => $temp->imagem_file_id,
                ));

                $this->arquivos_m->update($temp->imagem_file_id, array(
                    'temp' => FALSE,
                ));
            }

            $this->db->trans_complete();

            $this->session->set_flashdata('success', 'Projetos atualizados com sucesso.');
            redirect('admin/projetos');
        }

        $this->layout->view('admin/projetos/index', $dados);
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
        $this->breadcrumbs[] = array('Projetos',
                site_url("admin/projetos"));
    }

    private function init_navbar($method)
    {
    }

}
