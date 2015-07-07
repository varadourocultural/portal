<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Atributos extends MY_Controller {

    private $validation_rules = array(
        array(
            'field' => 'atributo_ascendente',
            'label' => 'Atributo ascendente',
            'rules' => 'trim',
        ),

        array(
            'field' => 'tipo',
            'label' => 'Tipo',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'sigla',
            'label' => 'Sigla',
            'rules' => 'trim',
        ),

        array(
            'field' => 'icone_file_id',
            'label' => 'Ícone',
            'rules' => 'trim',
        ),
    );

    private $tipos_atributos = array(
        'selecao-simples' => 'Seleção símples',
        'selecao-multipla' => 'Seleção múltipla',
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

        $this->load->model('atributos_m');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');

        $this->load->vars(array(
            'tipos_atributos' => $this->tipos_atributos,
        ));
    }

    public function index($start, $atributo_ascendente)
    {
        checar_permissao('atributos.listar_registros');

        if (! empty($atributo_ascendente)) {
            $atributos = $this->atributos_m->get_all_filhos($atributo_ascendente, 20, $start);
        }
        else {
            $atributos = $this->atributos_m->get_all(FALSE, 20, $start);
        }

        if (! empty($atributo_ascendente)) {
            $config['base_url'] = site_url("admin/atributos-descendentes/{$atributo_ascendente}");
        }
        else {
            $config['base_url'] = site_url('admin/atributos');
        }

        $config['total_rows'] = $this->atributos_m->total_filhos($atributo_ascendente);
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $this->init_breadcrumbs($atributo_ascendente);
        $this->init_navbar('index', $atributo_ascendente);

        $dados = array(
            'atributo_ascendente' => $atributo_ascendente,
            'atributos' => $atributos,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'pagination' => $this->pagination->create_links(),
        );

        $this->layout->view('admin/atributos/index', $dados);
    }

    public function create($atributo_ascendente)
    {
        checar_permissao('atributos.inserir_registro');

        $atributo = new stdClass();

        $atributo->icone = NULL;

        if ($this->input->post() && $this->input->post("icone_file_id")) {
            $atributo->icone = $this->arquivos->obter($this->input->post("icone_file_id"));
        }

        $area_atuacao_cultural = FALSE;

        $first_ascendente = $this->atributos_m->get_first_ascendente($atributo_ascendente);

        if($first_ascendente){
            if($first_ascendente->slug == 'area-de-atuacao-cultural') {
                $area_atuacao_cultural = TRUE;
            }
        }

        if ($this->input->post()) {
            if (is_uploaded_file(@$_FILES['icone']['tmp_name'])) {
                $temp = $this->arquivos->adicionar(array(
                    'nome' => $_FILES['icone']['name'],
                    'caminho' => $_FILES['icone']['tmp_name'],
                    'tipo_mime' => $_FILES['icone']['type'],
                ), TRUE);

                if ($temp !== FALSE) {
                    $_POST['icone_file_id'] = $temp[0];
                    $atributo->icone = $this->arquivos->obter($temp[0]);
                }
            }

            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'atributo_ascendente' => $atributo_ascendente,
                    'tipo' => $this->input->post('tipo'),
                    'nome' => $this->input->post('nome'),
                    'sigla' => $this->input->post('sigla'),
                    'slug' => NULL,
                );

                if ($this->input->post('icone_file_id')) {
                    $data['icone_file_id'] = $this->input->post('icone_file_id');
                }

                $this->db->trans_start();

                if ($id = $this->atributos_m->insert($data)) {
                    if ($this->input->post('icone_file_id')) {
                        $this->arquivos_m->update($this->input->post('icone_file_id'), array(
                            'temp' => FALSE,
                        ));
                    }

                    $this->db->trans_complete();
                    $this->session->set_flashdata('success', 'Atributo adicionado com sucesso.');

                    if (! empty($atributo_ascendente)) {
                        redirect("admin/atributos-descendentes/{$atributo_ascendente}");
                    }
                    else {
                        redirect('admin/atributos');
                    }
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar atributo.');

                    if (! empty($atributo_ascendente)) {
                        redirect("admin/atributos-descendentes/{$atributo_ascendente}");
                    }
                    else {
                        redirect('admin/atributos');
                    }
                }
            }
        }

        foreach ($this->validation_rules as $rule) {
            $atributo->{$rule['field']} = $this->input->post($rule['field']);
        }

        $this->init_breadcrumbs($atributo_ascendente);

        if (! empty($atributo_ascendente)) {
            $this->breadcrumbs[] = array('Novo', site_url("admin/atributos-descendentes/create/{$atributo_ascendente}"));
        }
        else {
            $this->breadcrumbs[] = array('Novo', site_url("admin/atributos/create"));
        }

        $this->init_navbar('create', $atributo_ascendente);

        $dados = array(
            'atributo_ascendente' => $atributo_ascendente,
            'atributo' => $atributo,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'area_atuacao_cultural' => $area_atuacao_cultural,
        );

        $this->layout->view('admin/atributos/form', $dados);
    }

    public function edit($id)
    {
        checar_permissao('atributos.modificar_registro');

        $atributo = $this->atributos_m->get($id);

        if (! $atributo)
            show_404();

        $atributo->icone = NULL;

        $area_atuacao_cultural = FALSE;

        $first_ascendente = $this->atributos_m->get_first_ascendente($id);

        if($atributo->slug != 'area-de-atuacao-cultural') {
            if($first_ascendente->slug == 'area-de-atuacao-cultural') {
                $area_atuacao_cultural = TRUE;
            }
        }

        if ($this->input->post()) {
            if ($this->input->post('icone_file_id')) {
                $atributo->icone = $this->arquivos->obter($this->input->post('icone_file_id'));
            }
            else {
                $atributo->icone = NULL;
            }
        }
        elseif ($atributo->icone_file_id) {
            $atributo->icone = $this->arquivos->obter($atributo->icone_file_id);
        }
        else {
            $atributo->icone = NULL;
        }

        if ($this->input->post()) {
            if (is_uploaded_file(@$_FILES['icone']['tmp_name'])) {
                $temp = $this->arquivos->adicionar(array(
                    'nome' => $_FILES['icone']['name'],
                    'caminho' => $_FILES['icone']['tmp_name'],
                    'tipo_mime' => $_FILES['icone']['type'],
                ), TRUE);

                if ($temp !== FALSE) {
                    $_POST['icone_file_id'] = $temp[0];
                    $atributo->icone = $this->arquivos->obter($temp[0]);
                }
            }

            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $data = array(
                    'tipo' => $this->input->post('tipo'),
                    'nome' => $this->input->post('nome'),
                    'sigla' => $this->input->post('sigla'),
                );

                if ($this->input->post('icone_file_id')) {
                    $data['icone_file_id'] = $this->input->post('icone_file_id');
                }

                $this->db->trans_start();

                if ($this->atributos_m->update($id, $data)) {
                    if ($this->input->post('icone_file_id')) {
                        if ($atributo->icone_file_id) {
                            $this->arquivos_m->update($atributo->icone_file_id, array(
                                'temp' => TRUE,
                            ));
                        }

                        $this->arquivos_m->update($this->input->post('icone_file_id'), array(
                            'temp' => FALSE,
                        ));
                    }

                    $this->db->trans_complete();
                    $this->session->set_flashdata('success', 'Atributo atualizado com sucesso.');

                    if (! empty($atributo->atributo_ascendente)) {
                        redirect("admin/atributos-descendentes/{$atributo->atributo_ascendente}");
                    }
                    else {
                        redirect('admin/atributos');
                    }
                }
                else {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('error', 'Erro ao atualizar atributo.');

                    if (! empty($atributo->atributo_ascendente)) {
                        redirect("admin/atributos-descendentes/{$atributo->atributo_ascendente}");
                    }
                    else {
                        redirect('admin/atributos');
                    }
                }
            }

            foreach ($this->validation_rules as $rule) {
                $atributo->{$rule['field']} = $this->input->post($rule['field']);
            }
        }

        $this->init_breadcrumbs($atributo->atributo_ascendente);

        if (! empty($atributo->atributo_ascendente)) {
            $this->breadcrumbs[] = array('Editar', site_url("admin/atributos-descendentes/edit/$id"));
        }
        else {
            $this->breadcrumbs[] = array('Editar', site_url("admin/atributos/edit/$id"));
        }

        $this->init_navbar('edit', $atributo->atributo_ascendente);

        $dados = array(
            'atributo_ascendente' => $atributo->atributo_ascendente,
            'atributo' => $atributo,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'area_atuacao_cultural' => $area_atuacao_cultural,
        );

        $this->layout->view('admin/atributos/form', $dados);
    }

    public function sort($id, $dir)
    {
        checar_permissao('atributos.modificar_registro');

        $atributo = $this->atributos_m->get($id) or show_404();

        $atributos = $this->atributos_m->get_all_filhos($atributo->atributo_ascendente);
        $curr_idx = -1;

        for ($i = 0; $i < count($atributos); $i++) {
            if ($atributos[$i]->id == $id) {
                $curr_idx = $i;

                break;
            }
        }

        if ($curr_idx != -1) {
            $new_idx = $curr_idx + (intval($dir) * 1);

            if (($new_idx >= 0) && ($new_idx < count($atributos))) {
                $temp = $atributos[$new_idx];
                $atributos[$new_idx] = $atributos[$curr_idx];
                $atributos[$curr_idx] = $temp;
            }
        }

        $this->db->trans_start();

        for ($i = 0; $i < count($atributos); $i++) {
            $this->atributos_m
                    ->update($atributos[$i]->id, array('ordem' => $i + 1));
        }

        $this->db->trans_complete();

        if (! empty($atributo->atributo_ascendente)) {
            redirect("admin/atributos-descendentes/{$atributo->atributo_ascendente}");
        }
        else {
            redirect('admin/atributos');
        }
    }

    public function delete($id)
    {
        checar_permissao('atributos.excluir_registro');

        $atributo = $this->atributos_m->get($id) or show_404();

        $this->db->trans_start();

        if ($this->atributos_m->delete($atributo->id)) {
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Atributo excluído com sucesso.');

            if (! empty($atributo->atributo_ascendente)) {
                redirect("admin/atributos-descendentes/{$atributo->atributo_ascendente}");
            }
            else {
                redirect('admin/atributos');
            }
        }
        else {
            $this->session->set_flashdata('error', 'Erro ao excluir atributo.');

            if (! empty($atributo->atributo_ascendente)) {
                redirect("admin/atributos-descendentes/{$atributo->atributo_ascendente}");
            }
            else {
                redirect('admin/atributos');
            }
        }
    }

    public function delete_many()
    {
        checar_permissao('atributos.excluir_registro');

        $atributo_ascendente = NULL;
        $atributos = (array) $this->input->post('atributos');

        foreach ($atributos as $atributo_id) {
            $atributo = $this->atributos_m->get($atributo_id);

            if (! $atributo)
                continue;

            $atributo_ascendente = $atributo->atributo_ascendente;
            $this->db->trans_start();
            $this->atributos_m->delete($atributo->id);
            $this->db->trans_complete();
        }

        $this->session->set_flashdata('success', 'Atributo(s) excluído(s) com sucesso.');

        if ($atributo_ascendente) {
            redirect("admin/atributos-descendentes/{$atributo_ascendente}");
        }
        else {
            redirect('admin/atributos');
        }
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

    private function init_breadcrumbs($atributo_ascendente)
    {
        $this->breadcrumbs[] = array('Listagem de atributos',
                site_url("admin/atributos"));

        if (! empty($atributo_ascendente)) {
            $ascendentes = $this->atributos_m->get_all_ascendentes($atributo_ascendente);

            foreach (array_reverse($ascendentes) as $ascendente) {
                $this->breadcrumbs[] = array($ascendente->nome,
                        site_url("admin/atributos-descendentes/{$ascendente->id}"));
            }
        }
    }

    private function init_navbar($method, $atributo_ascendente)
    {
        if ($method == 'index') {
            if (! empty($atributo_ascendente)) {
                $this->navbar[] =  '<li class="pull-right"><a href="' . site_url("admin/atributos-descendentes/create"
                        . "/{$atributo_ascendente}")
                        . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Atributo</a></li>';
            }
            else {
                $this->navbar[] =  '<li class="pull-right"><a href="' . site_url("admin/atributos/create")
                        . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Atributo</a></li>';
            }
        }
    }

}
