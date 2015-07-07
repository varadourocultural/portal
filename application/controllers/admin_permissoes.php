<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Permissoes extends MY_Controller {

    private $validation_rules = array(
        array(
            'field' => 'id',
            'label' => 'Código',
            'rules' => 'trim',
        ),

        array(
            'field' => 'acao',
            'label' => 'Ação',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'nivel',
            'label' => 'Nível',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'permitir',
            'label' => 'Permitir',
            'rules' => 'trim',
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->lang->load('form_validation');
        $this->load->library('pagination');
        $this->load->library('arquivos');
        $this->load->model('arquivos_m');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('permissoes');

        $this->load->model('permissoes_m');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');
    }

    public function index($start = NULL)
    {
        checar_permissao('usuarios.permissoes.listar_registros');

        $permissoes = $this->permissoes_m->get_all(100, $start);

        $config['base_url'] = site_url('admin/permissoes');
        $config['total_rows'] = $this->permissoes_m->total();
        $config['per_page'] = 100;
        $config['uri_segment'] = 2;

        $this->pagination->initialize($config);

        $this->init_breadcrumbs();
        $this->init_navbar('index');

        $dados = array(
            'permissoes' => $permissoes,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'pagination' => $this->pagination->create_links(),
        );

        $this->layout->view('admin/permissoes/index', $dados);
    }

    public function create()
    {
        checar_permissao('usuarios.permissoes.inserir_registro');

        $permissao = new stdClass();

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'acao' => $this->input->post('acao'),
                    'nivel' => intval($this->input->post('nivel')),
                    'permitir' => intval($this->input->post('permitir')),
                    'ordem' => $this->permissoes_m->get_proxima_ordem(),
                );

                $this->db->trans_start();

                if ($id = $this->permissoes_m->insert($data)) {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('success', 'Permissão adicionado com sucesso.');
                    redirect('admin/permissoes');
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar permissão.');
                    redirect('admin/permissoes');
                }
            }
        }

        foreach ($this->validation_rules as $rule) {
            $permissao->{$rule['field']} = $this->input->post($rule['field']);
        }

        $this->init_breadcrumbs();

        $this->breadcrumbs[] = array('Novo', site_url("admin/permissoes/create"));

        $this->init_navbar('create');

        $dados = array(
            'permissao' => $permissao,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/permissoes/form', $dados);
    }

    public function edit($id)
    {
        checar_permissao('usuarios.permissoes.modificar_registro');

        $permissao = $this->permissoes_m->get($id);

        if (! $permissao)
            show_404();

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'acao' => $this->input->post('acao'),
                    'nivel' => intval($this->input->post('nivel')),
                    'permitir' => intval($this->input->post('permitir')),
                );

                $this->db->trans_start();

                if ($this->permissoes_m->update($id, $data)) {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('success', 'Permissão atualizado com sucesso.');
                    redirect('admin/permissoes');
                }
                else {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('error', 'Erro ao atualizar permissão.');
                    redirect('admin/permissoes');
                }
            }

            foreach ($this->validation_rules as $rule) {
                $permissao->{$rule['field']} = $this->input->post($rule['field']);
            }
        }

        $this->init_breadcrumbs();

        $this->breadcrumbs[] = array('Editar', site_url("admin/permissoes/edit/$id"));

        $this->init_navbar('edit');

        $dados = array(
            'permissao' => $permissao,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/permissoes/form', $dados);
    }

    public function sort($id, $dir)
    {
        checar_permissao('usuarios.permissoes.modificar_registro');

        $permissao = $this->permissoes_m->get($id) or show_404();

        $permissoes = $this->permissoes_m->get_all();
        $curr_idx = -1;

        for ($i = 0; $i < count($permissoes); $i++) {
            if ($permissoes[$i]->id == $id) {
                $curr_idx = $i;

                break;
            }
        }

        if ($curr_idx != -1) {
            $new_idx = $curr_idx + (intval($dir) * 1);

            if (($new_idx >= 0) && ($new_idx < count($permissoes))) {
                $temp = $permissoes[$new_idx];
                $permissoes[$new_idx] = $permissoes[$curr_idx];
                $permissoes[$curr_idx] = $temp;
            }
        }

        $this->db->trans_start();

        for ($i = 0; $i < count($permissoes); $i++) {
            $this->permissoes_m
                    ->update($permissoes[$i]->id, array('ordem' => $i + 1));
        }

        $this->db->trans_complete();

        redirect('admin/permissoes');
    }

    public function delete($id)
    {
        checar_permissao('usuarios.permissoes.excluir_registro');

        $permissao = $this->permissoes_m->get($id) or show_404();

        $this->db->trans_start();

        if ($this->permissoes_m->delete($permissao->id)) {
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Permissão excluído com sucesso.');
            redirect('admin/permissoes');
        }
        else {
            $this->session->set_flashdata('error', 'Erro ao excluir permissão.');
            redirect('admin/permissoes');
        }
    }

    public function delete_many()
    {
        checar_permissao('usuarios.permissoes.excluir_registro');

        $permissoes = (array) $this->input->post('permissoes');

        foreach ($permissoes as $permissao_id) {
            $permissao = $this->permissoes_m->get($permissao_id);

            if (! $permissao)
                continue;

            $this->db->trans_start();
            $this->permissoes_m->delete($permissao->id);
            $this->db->trans_complete();
        }

        $this->session->set_flashdata('success', 'Permissão(s) excluído(s) com sucesso.');
        redirect('admin/permissoes');
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
        $this->breadcrumbs[] = array('Listagem de permissões',
                site_url("admin/permissoes"));
    }

    private function init_navbar($method)
    {
        if ($method == 'index') {
            $this->navbar[] =  '<li class="pull-right"><a href="' . site_url("admin/permissoes/create")
                    . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Permissão</a></li>';
        }
    }

}
