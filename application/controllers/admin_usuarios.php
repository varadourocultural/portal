<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Usuarios extends MY_Controller {

    private $validation_rules = array(
        array(
            'field' => 'ativo',
            'label' => 'Ativo',
            'rules' => 'trim',
        ),

        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email',
        ),

        array(
            'field' => 'senha',
            'label' => 'Senha',
            'rules' => 'callback__valida_senha',
        ),

        array(
            'field' => 'nivel',
            'label' => 'Nível',
            'rules' => 'trim',
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('form_validation');

        $this->load->library('form_validation');
        $this->load->library('pagination');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('permissoes');

        $this->load->model('usuarios_m');
        $this->load->model('perfis_site_m');

        $this->breadcrumbs = array(
            array('Listagem de usuários', site_url("admin/usuarios")),
        );

        $this->navbar = array(
            '<li class="pull-right"><a href="' . site_url("admin/usuarios/create") . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Usuário</a></li>',

            '<li class="pull-right"><a href="' . site_url("admin/permissoes") . '" class="btn btn-small btn-info"><i class="icon-white icon-lock"></i> Permissões</a></li>',
        );

        $this->layout->set_layout('admin/layout_default');
    }

    public function index($start = NULL)
    {
        checar_permissao('usuarios.listar_registros');

        $usuarios = $this->usuarios_m->get_all(20, $start);

        $config['base_url'] = site_url('admin/usuarios');
        $config['total_rows'] = $this->usuarios_m->total();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dados = array(
            'usuarios' => $usuarios,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'pagination' => $this->pagination->create_links(),
        );

        $this->layout->view('admin/usuarios/index', $dados);
    }

    public function create()
    {
        checar_permissao('usuarios.inserir_registro');

        $usuario = new stdClass();
        $usuario->username = FALSE;

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'ativo' => intval($this->input->post('ativo')),
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'senha' => sha1($this->input->post('senha')),
                    'nivel' => intval($this->input->post('nivel')),
                );

                if ($id = $this->usuarios_m->insert($data)) {
                    $this->session->set_flashdata('success', 'Usuário adicionado com sucesso.');
                    redirect('admin/usuarios');
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar usuário.');
                    redirect('admin/usuarios');
                }
            }
        }

        foreach ($this->validation_rules as $rule) {
            $usuario->{$rule['field']} = $this->input->post($rule['field']);
        }

        $this->breadcrumbs[] = array('Novo', site_url("admin/usuarios/create"));

        $dados = array(
            'usuario' => $usuario,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/usuarios/form', $dados);
    }

    public function edit($id)
    {
        checar_permissao('usuarios.modificar_registro');

        $usuario = $this->usuarios_m->get($id) or show_404();
        $perfil = $this->perfis_site_m->get_by_usuario($id);

        if($perfil) {
            $usuario->username = $perfil->username;
            $usuario->sobrenome = $perfil->sobrenome;
        }
        else {
            $usuario->username = FALSE;
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $senha = $this->input->post('senha');

                $data = array(
                    'ativo' => intval($this->input->post('ativo')),
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'nivel' => intval($this->input->post('nivel')),
                );

                if (! empty($senha)) {
                    $data['senha'] = sha1($senha);
                }

                if ($this->usuarios_m->update($id, $data)) {

                    if($perfil) {
                        $data = array(
                                'username' => $this->input->post('username'),
                                'sobrenome' => $this->input->post('sobrenome'),
                            );

                        $this->perfis_site_m->update_by_usuario($id, $data);
                    }

                    $this->session->set_flashdata('success', 'Usuário atualizado com sucesso.');
                    redirect('admin/usuarios');
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao atualizar usuário.');
                    redirect('admin/usuarios');
                }
            }

            foreach ($this->validation_rules as $rule) {
                $usuario->{$rule['field']} = $this->input->post($rule['field']);
            }
        }

        $this->breadcrumbs[] = array('Editar', site_url("usuarios/edit/$id"));

        $dados = array(
            'usuario' => $usuario,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/usuarios/form', $dados);
    }

    public function delete($id)
    {
        checar_permissao('usuarios.excluir_registro');

        $this->usuarios_m->get($id) or show_404();

        if ($this->usuarios_m->delete($id)) {
            $this->session->set_flashdata('success', 'Usuário excluído com sucesso.');
            redirect('admin/usuarios');
        }
        else {
            $this->session->set_flashdata('error', 'Erro ao excluir usuário.');
            redirect('admin/usuarios');
        }
    }

    public function delete_many()
    {
        checar_permissao('usuarios.excluir_registro');

        $usuarios = (array) $this->input->post('usuarios');
        $this->usuarios_m->delete_many($usuarios);
        $this->session->set_flashdata('success', 'Usuário(s) excluído(s) com sucesso.');
        redirect('admin/usuarios');
    }

    public function _valida_senha($senha)
    {
        $method = $this->router->fetch_method();

        if ($method == 'create') {
            if (empty($senha)) {
                $this->form_validation->set_message('_valida_senha',
                        sprintf($this->lang->line('required'), 'Senha'));

                return FALSE;
            }
            elseif (strlen($senha) < 8) {
                $this->form_validation->set_message('_valida_senha',
                        sprintf($this->lang->line('min_length'), 'Senha', 8));

                return FALSE;
            }
            elseif (strlen($senha) > 16) {
                $this->form_validation->set_message('_valida_senha',
                        sprintf($this->lang->line('max_length'), 'Senha', 16));

                return FALSE;
            }
            elseif ($senha != $this->input->post('confirmasenha')) {
                $confirma = $this->input->post('confirmasenha');

                if (empty($confirma)) {
                    $this->form_validation->set_message('_valida_senha',
                            'Por favor confirme sua senha.');

                    return FALSE;
                }
                else {
                    $this->form_validation->set_message('_valida_senha',
                            'Sua confirmação de senha não confere.');

                    return FALSE;
                }
            }
            else {
                return TRUE;
            }
        }
        elseif ($method == 'edit') {
            if (! empty($senha)) {
                if (strlen($senha) < 8) {
                    $this->form_validation->set_message('_valida_senha',
                            sprintf($this->lang->line('min_length'), 'Senha', 8));

                    return FALSE;
                }
                elseif (strlen($senha) > 16) {
                    $this->form_validation->set_message('_valida_senha',
                            sprintf($this->lang->line('max_length'), 'Senha', 16));

                    return FALSE;
                }
                elseif ($senha != $this->input->post('confirmasenha')) {
                    $confirma = $this->input->post('confirmasenha');

                    if (empty($confirma)) {
                        $this->form_validation->set_message('_valida_senha',
                                'Por favor confirme sua nova senha.');

                        return FALSE;
                    }
                    else {
                        $this->form_validation->set_message('_valida_senha',
                                'Sua confirmação de senha não confere.');

                        return FALSE;
                    }
                }
            }
            else {
                return TRUE;
            }
        }
        else {
            return TRUE;
        }
    }

}
