<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Eventos extends MY_Controller {

    private $validation_rules = array(
        'status' => array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'trim',
        ),

        'id' => array(
            'field' => 'id',
            'label' => 'Código',
            'rules' => 'trim',
        ),

        'titulo' => array(
            'field' => 'titulo',
            'label' => 'Título',
            'rules' => 'trim|required',
        ),

        'slug' => array(
            'field' => 'slug',
            'label' => 'Slug',
            'rules' => 'trim|required',
        ),

        'data' => array(
            'field' => 'data',
            'label' => 'Data',
            'rules' => 'trim|required',
        ),

        'horario' => array(
            'field' => 'horario',
            'label' => 'Horario',
            'rules' => 'trim|required',
        ),

        'descricao' => array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'trim',
        ),

        'informacoes_valores' => array(
            'field' => 'informacoes_valores',
            'label' => 'Informações de valores',
            'rules' => 'trim|required',
        ),

        'informacoes_datas' => array(
            'field' => 'informacoes_datas',
            'label' => 'Informações de datas',
            'rules' => 'trim|required',
        ),

        'informacoes_horarios' => array(
            'field' => 'informacoes_horarios',
            'label' => 'Informaçoes de horários',
            'rules' => 'trim|required',
        ),

        'imagem_cover_file_id' => array(
            'field' => 'imagem_cover_file_id',
            'label' => 'Imagem de cover',
            'rules' => 'trim',
        ),
    );

    private $statuses =  array(
                'publico' => 'Público',
                'rascunho' => 'Rascunho'
            );

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->lang->load('form_validation');
        $this->load->library('pagination');
        $this->load->library('arquivos');
        $this->load->model('arquivos_m');
        $this->load->model('espacos_culturais_m');

        $this->load->model('agentes_culturais_eventos_m');
        $this->load->model('agentes_culturais_m');
        $this->load->model('eventos_fotos_m');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('permissoes');
        $this->load->helper('extra');
        $this->load->helper('util');

        $this->load->model('eventos_m');

        $this->config->load('elasticsearch');

        $this->load->library('slug');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');

        $this->load->vars(array(
            'statuses' => $this->statuses,
        ));
    }

    public function index($start, $espaco_cultural_id)
    {
        checar_permissao('espacos_culturais.eventos.listar_registros');

        $espaco_cultural = $this->espacos_culturais_m->get($espaco_cultural_id) or show_404();

        $eventos = $this->eventos_m->get_all_by_espaco_cultural($espaco_cultural->id, 20, $start);

        $config['base_url'] = site_url("admin/espaco-cultural/{$espaco_cultural->id}/eventos");
        $config['total_rows'] = $this->eventos_m->total_by_espaco_cultural($espaco_cultural->id);
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $this->init_breadcrumbs($espaco_cultural->id);
        $this->init_navbar('index', $espaco_cultural->id);

        $dados = array(
            'espaco_cultural' => $espaco_cultural->id,
            'eventos' => $eventos,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'pagination' => $this->pagination->create_links(),
        );

        $this->layout->view('admin/eventos/index', $dados);
    }

    public function create($espaco_cultural_id)
    {
        checar_permissao('espacos_culturais.eventos.inserir_registro');

        $espaco_cultural = $this->espacos_culturais_m->get($espaco_cultural_id) or show_404();

        $evento = new stdClass();
        $agentes = array();
        $evento->fotos = array();

        $all_agentes = $this->agentes_culturais_m->get_all();

        $evento->imagem_cover = NULL;

        if ($this->input->post() && $this->input->post("imagem_cover_file_id")) {
            $evento->imagem_cover = $this->arquivos->obter($this->input->post("imagem_cover_file_id"));
        }

        if ($this->input->post()) {
            if (checar_permissao('espacos_culturais.eventos.publicar_registro', NULL)) {
                $this->validation_rules['status']['rules'] .= '|required';
            }

            if (is_uploaded_file(@$_FILES['imagem_cover']['tmp_name'])) {
                $temp = $this->arquivos->adicionar(array(
                    'nome' => $_FILES['imagem_cover']['name'],
                    'caminho' => $_FILES['imagem_cover']['tmp_name'],
                    'tipo_mime' => $_FILES['imagem_cover']['type'],
                ), TRUE);

                if ($temp !== FALSE) {
                    $_POST['imagem_cover_file_id'] = $temp[0];
                    $evento->imagem_cover = $this->arquivos->obter($temp[0]);
                }
            }

            $agentes = $this->listar_agentes();
            $this->form_validation->set_rules($this->validation_rules);


            if ($this->form_validation->run()) {
                $this->slug->set_config(array(
                    'field' => 'slug',
                    'title' => 'titulo',
                    'table' => 'eventos',
                    'id' => 'id',
                ));

                if (checar_permissao('espacos_culturais.eventos.publicar_registro', NULL)) {
                    $status = $this->input->post('status');
                }
                else {
                    $status = 'rascunho';
                }

                $data = array(
                    'status' => $status,
                    'titulo' => $this->input->post('titulo'),
                    'slug' => $this->slug->create_uri($this->input->post('titulo')),
                    'espaco_cultural' => $espaco_cultural->id,
                    'data' => NULL,
                    'horario' => $this->input->post('horario'),
                    'descricao' => $this->input->post('descricao'),
                    'informacoes_valores' => $this->input->post('informacoes_valores'),
                    'informacoes_datas' => $this->input->post('informacoes_datas'),
                    'informacoes_horarios' => $this->input->post('informacoes_horarios'),
                );

                $temp = ler_data($this->input->post('data'));

                if ($temp)
                    $data['data'] = formata_data($temp, 'db_date');

                if ($this->input->post('imagem_cover_file_id')) {
                    $data['imagem_cover_file_id'] = $this->input->post('imagem_cover_file_id');
                }

                $this->db->trans_start();

                if ($id = $this->eventos_m->insert($data)) {
                    $evento->fotos = $this->listar_arquivos('foto');

                    if ($this->input->post('imagem_cover_file_id')) {
                        $this->arquivos_m->update($this->input->post('imagem_cover_file_id'), array(
                                    'temp' => FALSE,
                                ));
                    }

                    foreach ($evento->fotos as $temp) {
                        $this->eventos_fotos_m->insert($id, array(
                            'foto_file_id' => $temp->foto_file_id,
                        ));

                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => FALSE,
                        ));
                    }

                    foreach ($agentes as $agente) {
                        $this->agentes_culturais_eventos_m->insert($id, array(
                            'agente_cultural_id' => $agente->agente_id,
                        ));
                    }

                    $this->db->trans_complete();
                    $this->indexar_evento($id, $data);
                    $this->session->set_flashdata('success', 'Evento adicionado com sucesso.');
                    redirect("admin/espacos-culturais/{$espaco_cultural->id}/eventos");
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar evento.');
                    redirect("admin/espacos-culturais/{$espaco_cultural->id}/eventos");
                }
            }
        }

        foreach ($this->validation_rules as $rule) {
            $evento->{$rule['field']} = $this->input->post($rule['field']);
        }

        $evento->data = NULL;
        $temp = ler_data($this->input->post('data'));

        if ($temp)
            $evento->data = formata_data($temp);

        $this->init_breadcrumbs($espaco_cultural->id);

        $this->breadcrumbs[] = array('Novo', site_url("admin/espacos-culturais/{$espaco_cultural_id}/eventos/create"));

        $this->init_navbar('create', $espaco_cultural->id);

        $evento->agentes = $agentes;

        $dados = array(
            'espaco_cultural' => $espaco_cultural->id,
            'evento' => $evento,
            'agentes' => $all_agentes,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/eventos/form', $dados);
    }

    public function edit($id)
    {
        checar_permissao('espacos_culturais.eventos.modificar_registro');

        $evento = $this->eventos_m->get($id);

        if (! $evento)
            show_404();

        $temp = ler_data($evento->data, 'db_date');
        $evento->data = $temp ? formata_data($temp) : NULL;
        $evento->imagem_cover = NULL;
        $evento->fotos = $this->eventos_fotos_m->get_all_by_evento($evento->id);

        $all_agentes = $this->agentes_culturais_m->get_all();

        if ($this->input->post()) {
            if ($this->input->post('imagem_cover_file_id')) {
                $evento->imagem_cover = $this->arquivos->obter($this->input->post('imagem_cover_file_id'));
            }
            else {
                $evento->imagem_cover = NULL;
            }
        }
        elseif ($evento->imagem_cover_file_id) {
            $evento->imagem_cover = $this->arquivos->obter($evento->imagem_cover_file_id);
        }
        else {
            $evento->imagem_cover = NULL;
        }

        $agentes = $this->agentes_culturais_eventos_m->get_all_by_evento($id);

        if ($this->input->post()) {
            if (is_uploaded_file(@$_FILES['imagem_cover']['tmp_name'])) {
                $temp = $this->arquivos->adicionar(array(
                    'nome' => $_FILES['imagem_cover']['name'],
                    'caminho' => $_FILES['imagem_cover']['tmp_name'],
                    'tipo_mime' => $_FILES['imagem_cover']['type'],
                ), TRUE);

                if ($temp !== FALSE) {
                    $_POST['imagem_cover_file_id'] = $temp[0];
                    $evento->imagem_cover = $this->arquivos->obter($temp[0]);
                }
            }

            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $this->slug->set_config(array(
                    'field' => 'slug',
                    'title' => 'titulo',
                    'table' => 'eventos',
                    'id' => 'id',
                ));

                if (checar_permissao('espacos_culturais.eventos.publicar_registro', NULL)) {
                    $status = $this->input->post('status');
                }
                else {
                    $status = 'rascunho';
                }

                $data = array(
                    'status' => $status,
                    'titulo' => $this->input->post('titulo'),
                    'slug' => $this->slug->create_uri($this->input->post('titulo'), $id),
                    'descricao' => $this->input->post('descricao'),
                    'data' => NULL,
                    'horario' => $this->input->post('horario'),
                    'informacoes_valores' => $this->input->post('informacoes_valores'),
                    'informacoes_datas' => $this->input->post('informacoes_datas'),
                    'informacoes_horarios' => $this->input->post('informacoes_horarios'),
                );

                $temp = ler_data($this->input->post('data'));

                if ($temp)
                    $data['data'] = formata_data($temp, 'db_date');

                if ($this->input->post('imagem_cover_file_id')) {
                    $data['imagem_cover_file_id'] = $this->input->post('imagem_cover_file_id');
                }

                $this->db->trans_start();

                if ($this->eventos_m->update($id, $data)) {
                    if ($this->input->post('imagem_cover_file_id')) {
                        $data['imagem_cover_file_id'] = $this->input->post('imagem_cover_file_id');
                    }

                    $fotos_old = $evento->fotos;
                    $evento->fotos = $this->listar_arquivos('foto');

                    foreach ($fotos_old as $temp) {
                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => TRUE,
                        ));
                    }

                    $this->eventos_fotos_m->delete_all_by_evento($evento->id);

                    foreach ($evento->fotos as $temp) {
                        $this->eventos_fotos_m->insert($id, array(
                            'foto_file_id' => $temp->foto_file_id,
                        ));

                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => FALSE,
                        ));
                    }

                    $this->agentes_culturais_eventos_m->delete_all_by_evento($id);

                    foreach ($this->listar_agentes() as $agente) {
                        $this->agentes_culturais_eventos_m->insert($id, array(
                            'agente_cultural_id' => $agente->agente_id,
                        ));
                    }

                    $this->db->trans_complete();
                    $this->reindexar_evento($id, $data);
                    $this->session->set_flashdata('success', 'Evento atualizado com sucesso.');
                    redirect("admin/espacos-culturais/{$evento->espaco_cultural}/eventos");
                }
                else {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('error', 'Erro ao atualizar evento.');
                    redirect("admin/espacos-culturais/{$evento->espaco_cultural}/eventos");
                }
            }

            foreach ($this->validation_rules as $rule) {
                $evento->{$rule['field']} = $this->input->post($rule['field']);
            }

            $temp = ler_data($this->input->post('data'));
            $evento->data = $temp ? formata_data($temp) : NULL;
        }

        $this->init_breadcrumbs($evento->espaco_cultural);

        $this->breadcrumbs[] = array('Editar', site_url("admin/espacos-culturais/{$evento->espaco_cultural}/eventos/edit/$id"));

        $this->init_navbar('edit', $evento->espaco_cultural);

        $evento->agentes = array();

        foreach ($agentes as $agente) {
            array_push($evento->agentes, $this->agentes_culturais_m->get($agente->agente_cultural_id));
        }

        $dados = array(
            'espaco_cultural' => $evento->espaco_cultural,
            'evento' => $evento,
            'agentes' => $all_agentes,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/eventos/form', $dados);
    }

    public function delete($id)
    {
        checar_permissao('espacos_culturais.eventos.excluir_registro');

        $evento = $this->eventos_m->get($id) or show_404();

        $this->db->trans_start();

        if ($this->eventos_m->delete($evento->id)) {
            $this->db->trans_complete();
            $this->desindexar_evento($evento->id);
            $this->session->set_flashdata('success', 'Evento excluído com sucesso.');
            redirect("admin/espacos-culturais/{$evento->espaco_cultural}/eventos");
        }
        else {
            $this->session->set_flashdata('error', 'Erro ao excluir evento.');
            redirect("admin/espacos-culturais/{$evento->espaco_cultural}/eventos");
        }
    }

    public function delete_many()
    {
        checar_permissao('espacos_culturais.eventos.excluir_registro');

        $eventos = (array) $this->input->post('eventos');
        $espaco_cultural_id = NULL;

        foreach ($eventos as $evento_id) {
            $evento = $this->eventos_m->get($evento_id);

            if (! $evento)
                continue;

            $espaco_cultural_id = $evento-espaco_cultural;
            $this->db->trans_start();
            $this->eventos_m->delete($evento->id);
            $this->db->trans_complete();
            $this->desindexar_evento($evento->id);
        }

        $this->session->set_flashdata('success', 'Evento(s) excluído(s) com sucesso.');

        if ($espaco_cultural_id) {
            redirect("admin/espacos-culturais/{$espaco_cultural_id}/eventos");
        }
        else {
            redirect("admin/espacos-culturais");
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

    private function listar_agentes()
    {
        $agentes = array();
        $id = (array) $this->input->post('id_agente');
        $nome = (array) $this->input->post('nome_agente');
        $total = count($id);

        for ($i = 1; $i < $total; $i++) {
            $agente = new stdClass();

            $agente->agente_id = $id[$i];
            $agente->nome = $nome[$i];

            $agentes[] = $agente;
        }

        return $agentes;
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

    private function init_breadcrumbs($espaco_cultural_id)
    {
        $this->breadcrumbs[] = array('Listagem de espaços culturais',
                site_url("admin/espacos-culturais"));

        $this->breadcrumbs[] = array('Editar',
                site_url("admin/espacos-culturais/edit/$espaco_cultural_id"));

        $this->breadcrumbs[] = array('Listagem de eventos',
                site_url("admin/espacos-culturais/{$espaco_cultural_id}/eventos"));
    }

    private function init_navbar($method, $espaco_cultural_id)
    {
        if ($method == 'index') {
            $this->navbar[] =  '<li class="pull-right"><a href="'
                    . site_url("admin/espacos-culturais/{$espaco_cultural_id}/eventos/create")
                    . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Evento</a></li>';
        }
    }

    private function indexar_evento($id, $dados_evento)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->index(array(
            'body' => array(
                'titulo' => $dados_evento['titulo'],
                'slug' => $dados_evento['slug'],
                'descricao' => strip_tags_better($dados_evento['descricao']),
                'data' => $dados_evento['data'],
            ),

            'index' => 'varadouro',

            'type' => 'evento',

            'id' => $id,
        ));
    }

    private function reindexar_evento($id, $dados_evento)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->update(array(
            'body' => array(
                'doc' => array(
                    'titulo' => $dados_evento['titulo'],
                    'slug' => $dados_evento['slug'],
                    'descricao' => strip_tags_better($dados_evento['descricao']),
                    'data' => $dados_evento['data'],
                ),
            ),

            'index' => 'varadouro',

            'type' => 'evento',

            'id' => $id,
        ));
    }

    private function desindexar_evento($id)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->delete(array(
            'index' => 'varadouro',

            'type' => 'evento',

            'id' => $id,
        ));
    }

}
