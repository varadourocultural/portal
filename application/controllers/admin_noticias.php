<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Noticias extends MY_Controller {

    private $validation_rules = array(
        array(
            'field' => 'titulo',
            'label' => 'Título',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'slug',
            'label' => 'Slug',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'conteudo',
            'label' => 'Conteúdo',
            'rules' => 'trim|required',
        ),

        array(
            'field' => 'autor',
            'label' => 'Autor',
            'rules' => 'trim',
        ),

        array(
            'field' => 'data',
            'label' => 'Data',
            'rules' => 'trim|required',
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

        $this->load->model('noticias_fotos_m');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('permissoes');
        $this->load->model('noticias_m');
        $this->load->helper('extra');
        $this->load->helper('util');

        $this->config->load('elasticsearch');

        $this->load->library('slug');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');
    }

    public function index($start = NULL)
    {
        checar_permissao('noticias.listar_registros');

        $noticias = $this->noticias_m->get_all(20, $start);

        $config['base_url'] = site_url('admin/noticias/index');
        $config['total_rows'] = $this->noticias_m->total();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $this->init_breadcrumbs();
        $this->init_navbar('index');

        $dados = array(
            'noticias' => $noticias,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'pagination' => $this->pagination->create_links(),
        );

        $this->layout->view('admin/noticias/index', $dados);
    }

    public function create()
    {
        checar_permissao('noticias.inserir_registro');

        $noticia = new stdClass();

        $noticia->fotos = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $this->slug->set_config(array(
                    'field' => 'slug',
                    'title' => 'titulo',
                    'table' => 'noticias',
                    'id' => 'id',
                ));

                $data = array(
                    'titulo' => $this->input->post('titulo'),
                    'slug' => $this->slug->create_uri($this->input->post('titulo')),
                    'conteudo' => $this->input->post('conteudo'),
                    'autor' => $this->input->post('autor'),
                    'data' => NULL,
                    'ordem' => $this->noticias_m->get_proxima_ordem(),
                );

                $temp = ler_data($this->input->post('data'));

                if ($temp)
                    $data['data'] = formata_data_hora($temp, 'db_date');

                $this->db->trans_start();

                if ($id = $this->noticias_m->insert($data)) {
                    $noticia->fotos = $this->listar_arquivos('foto');

                    foreach ($noticia->fotos as $temp) {
                        $this->noticias_fotos_m->insert($id, array(
                            'foto_file_id' => $temp->foto_file_id,
                        ));

                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => FALSE,
                        ));
                    }

                    $this->db->trans_complete();
                    $this->indexar_noticia($id, $data);
                    $this->session->set_flashdata('success', 'Notícia adicionado com sucesso.');
                    redirect('admin/noticias');
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar notícia.');
                    redirect('admin/noticias');
                }
            }
        }

        foreach ($this->validation_rules as $rule) {
            $noticia->{$rule['field']} = $this->input->post($rule['field']);
        }

        $noticia->data = NULL;
        $temp = ler_data($this->input->post('data'));

        if ($temp)
            $noticia->data = formata_data($temp);

        $this->init_breadcrumbs();

        $this->breadcrumbs[] = array('Novo', site_url("admin/noticias/create"));

        $this->init_navbar('create');

        $dados = array(
            'noticia' => $noticia,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/noticias/form', $dados);
    }

    public function edit($id)
    {
        checar_permissao('noticias.modificar_registro');

        $noticia = $this->noticias_m->get($id);

        if (! $noticia)
            show_404();

        $temp = ler_data_hora($noticia->data, 'db_date');
        $noticia->data = $temp ? formata_data($temp) : NULL;

        $noticia->fotos = $this->noticias_fotos_m->get_all_by_noticia($noticia->id);

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $this->slug->set_config(array(
                    'field' => 'slug',
                    'title' => 'titulo',
                    'table' => 'noticias',
                    'id' => 'id',
                ));

                $data = array(
                    'titulo' => $this->input->post('titulo'),
                    'slug' => $this->slug->create_uri($this->input->post('titulo'), $id),
                    'conteudo' => $this->input->post('conteudo'),
                    'autor' => $this->input->post('autor'),
                    'data' => NULL,
                );

                $temp = ler_data($this->input->post('data'));

                if ($temp)
                    $data['data'] = formata_data_hora($temp, 'db_date');

                $this->db->trans_start();

                if ($this->noticias_m->update($id, $data)) {
                    $fotos_old = $noticia->fotos;
                    $noticia->fotos = $this->listar_arquivos('foto');

                    foreach ($fotos_old as $temp) {
                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => TRUE,
                        ));
                    }

                    $this->noticias_fotos_m->delete_all_by_noticia($noticia->id);

                    foreach ($noticia->fotos as $temp) {
                        $this->noticias_fotos_m->insert($id, array(
                            'foto_file_id' => $temp->foto_file_id,
                        ));

                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => FALSE,
                        ));
                    }

                    $this->db->trans_complete();
                    $this->reindexar_noticia($id, $data);
                    $this->session->set_flashdata('success', 'Notícia atualizada com sucesso.');
                    redirect('admin/noticias');
                }
                else {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('error', 'Erro ao atualizar notícia.');
                    redirect('admin/noticias');
                }
            }

            foreach ($this->validation_rules as $rule) {
                $noticia->{$rule['field']} = $this->input->post($rule['field']);
            }

            $temp = ler_data($this->input->post('data'));
            $noticia->data = $temp ? formata_data($temp) : NULL;
        }

        $this->init_breadcrumbs();

        $this->breadcrumbs[] = array('Editar', site_url("admin/noticias/edit/$id"));

        $this->init_navbar('edit');

        $dados = array(
            'noticia' => $noticia,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/noticias/form', $dados);
    }

    public function sort($id, $dir)
    {
        checar_permissao('noticias.modificar_registro');

        $noticia = $this->noticias_m->get($id) or show_404();

        $noticias = $this->noticias_m->get_all();
        $curr_idx = -1;

        for ($i = 0; $i < count($noticias); $i++) {
            if ($noticias[$i]->id == $id) {
                $curr_idx = $i;

                break;
            }
        }

        if ($curr_idx != -1) {
            $new_idx = $curr_idx + (intval($dir) * 1);

            if (($new_idx >= 0) && ($new_idx < count($noticias))) {
                $temp = $noticias[$new_idx];
                $noticias[$new_idx] = $noticias[$curr_idx];
                $noticias[$curr_idx] = $temp;
            }
        }

        $this->db->trans_start();

        for ($i = 0; $i < count($noticias); $i++) {
            $this->noticias_m
                    ->update($noticias[$i]->id, array('ordem' => $i + 1));
        }

        $this->db->trans_complete();

        redirect('admin/noticias');
    }

    public function delete($id)
    {
        checar_permissao('noticias.excluir_registro');

        $noticia = $this->noticias_m->get($id) or show_404();

        $this->db->trans_start();

        if ($this->noticias_m->delete($noticia->id)) {
            $this->db->trans_complete();
            $this->desindexar_noticia($noticia->id);
            $this->session->set_flashdata('success', 'Notícia excluído com sucesso.');
            redirect('admin/noticias');
        }
        else {
            $this->session->set_flashdata('error', 'Erro ao excluir notícia.');
            redirect('admin/noticias');
        }
    }

    public function delete_many()
    {
        checar_permissao('noticias.excluir_registro');

        $noticias = (array) $this->input->post('noticias');

        foreach ($noticias as $noticia_id) {
            $noticia = $this->noticias_m->get($noticia_id);

            if (! $noticia)
                continue;

            $this->db->trans_start();
            $this->noticias_m->delete($noticia->id);
            $this->db->trans_complete();
            $this->desindexar_noticia($noticia->id);
        }

        $this->session->set_flashdata('success', 'Notícia(s) excluído(s) com sucesso.');
        redirect('admin/noticias');
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
        $this->breadcrumbs[] = array('Listagem de notícias',
                site_url("admin/noticias"));
    }

    private function init_navbar($method)
    {
        if ($method == 'index') {
            $this->navbar[] =  '<li class="pull-right"><a href="' . site_url("admin/noticias/create")
                    . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Notícia</a></li>';
        }
    }

    private function indexar_noticia($id, $dados_noticia)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->index(array(
            'body' => array(
                'data' => $dados_noticia['data'],
                'titulo' => $dados_noticia['titulo'],
                'slug' => $dados_noticia['slug'],
                'conteudo' => strip_tags_better($dados_noticia['conteudo']),
                'autor' => $dados_noticia['autor'],
            ),

            'index' => 'varadouro',

            'type' => 'noticia',

            'id' => $id,
        ));
    }

    private function reindexar_noticia($id, $dados_noticia)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->update(array(
            'body' => array(
                'doc' => array(
                    'data' => $dados_noticia['data'],
                    'titulo' => $dados_noticia['titulo'],
                    'slug' => $dados_noticia['slug'],
                    'conteudo' => strip_tags_better($dados_noticia['conteudo']),
                ),
            ),

            'index' => 'varadouro',

            'type' => 'noticia',

            'id' => $id,
        ));
    }

    private function desindexar_noticia($id)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->delete(array(
            'index' => 'varadouro',

            'type' => 'noticia',

            'id' => $id,
        ));
    }

}
