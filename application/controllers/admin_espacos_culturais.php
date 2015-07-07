<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Espacos_Culturais extends MY_Controller {

    private $validation_rules = array(
        'status' => array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'trim',
        ),

        'nome_espaco' => array(
            'field' => 'nome_espaco',
            'label' => 'Nome do espaço',
            'rules' => 'trim|required',
        ),

        'slug' => array(
            'field' => 'slug',
            'label' => 'Slug',
            'rules' => 'trim|required',
        ),

        'natureza_juridica' => array(
            'field' => 'natureza_juridica',
            'label' => 'Natureza jurídica',
            'rules' => 'trim|required',
        ),

        'tipo_espaco' => array(
            'field' => 'tipo_espaco',
            'label' => 'Tipo do espaço',
            'rules' => 'trim|required',
        ),

        'nome_responsavel' => array(
            'field' => 'nome_responsavel',
            'label' => 'Nome do responsável',
            'rules' => 'trim|required',
        ),

        'espaco_fisico_virtual' => array(
            'field' => 'espaco_fisico_virtual',
            'label' => 'Espaço físico/virtual',
            'rules' => '',
        ),

        'ponto_cultura' => array(
            'field' => 'ponto_cultura',
            'label' => 'Ponto de cultura',
            'rules' => 'trim',
        ),

        'area_atuacao_primaria' => array(
            'field' => 'area_atuacao_primaria',
            'label' => 'Área de atuação cultural primária',
            'rules' => 'trim|required',
        ),

        'area_atuacao_cultural' => array(
            'field' => 'area_atuacao_cultural',
            'label' => 'Área de atuação cultural',
            'rules' => 'callback__checar_area_atuacao_cultural',
        ),

        'endereco' => array(
            'field' => 'endereco',
            'label' => 'Endereço',
            'rules' => 'trim|required',
        ),

        'cep' => array(
            'field' => 'cep',
            'label' => 'Cep',
            'rules' => 'trim|required',
        ),

        'complemento' => array(
            'field' => 'complemento',
            'label' => 'Complemento',
            'rules' => 'trim',
        ),

        'latitude' => array(
            'field' => 'latitude',
            'label' => 'Latitude',
            'rules' => 'trim',
        ),

        'longitude' => array(
            'field' => 'longitude',
            'label' => 'Longitude',
            'rules' => 'trim',
        ),

        'atividades_culturais' => array(
            'field' => 'atividades_culturais',
            'label' => 'Atividades culturais',
            'rules' => 'trim',
        ),

        'celular' => array(
            'field' => 'celular',
            'label' => 'Celular',
            'rules' => 'trim',
        ),

        'telefone_fixo' => array(
            'field' => 'telefone_fixo',
            'label' => 'Telefone fixo',
            'rules' => 'trim',
        ),

        'telefone_comercial' => array(
            'field' => 'telefone_comercial',
            'label' => 'Telefone trab./comercial',
            'rules' => 'trim|required',
        ),

        'site' => array(
            'field' => 'site',
            'label' => 'End. Site',
            'rules' => 'trim',
        ),

        'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required',
        ),

        'preco_minimo' => array(
            'field' => 'preco_minimo',
            'label' => 'Preço mínimo',
            'rules' => 'trim',
        ),

        'preco_maximo' => array(
            'field' => 'preco_maximo',
            'label' => 'Preço máximo',
            'rules' => 'trim',
        ),

        'url_agenda_cultural' => array(
            'field' => 'url_agenda_cultural',
            'label' => 'URL agenda cultural',
            'rules' => 'trim',
        ),

        'informacoes_preco' => array(
            'field' => 'informacoes_preco',
            'label' => 'Informações preço',
            'rules' => 'trim',
        ),

        'fechado_almoco' => array(
            'field' => 'fechado_almoco',
            'label' => 'Fechado almoço',
            'rules' => 'trim',
        ),

        'twitter' => array(
            'field' => 'twitter',
            'label' => 'Twitter',
            'rules' => 'trim',
        ),

        'facebook' => array(
            'field' => 'facebook',
            'label' => 'Facebook',
            'rules' => 'trim|required',
        ),

        'google_plus' => array(
            'field' => 'google_plus',
            'label' => 'Google+',
            'rules' => 'trim',
        ),

        'youtube' => array(
            'field' => 'youtube',
            'label' => 'You Tube',
            'rules' => 'trim',
        ),

        'outras_redes_sociais' => array(
            'field' => 'outras_redes_sociais',
            'label' => 'Outras redes sociais',
            'rules' => 'trim',
        ),

        'informacoes_adicionais' => array(
            'field' => 'informacoes_adicionais',
            'label' => 'Informações adicionais',
            'rules' => 'trim',
        ),

        'horario_funcionamento' => array(
            'field' => 'horario_funcionamento',
            'label' => 'Horário de funcionemento',
            'rules' => '',
        ),
    );

    private $horarios = array(
        '00:00:00' => '00:00', '00:30:00' => '00:30', '01:00:00' => '01:00',
        '01:30:00' => '01:30', '02:00:00' => '02:00', '02:30:00' => '02:30',
        '03:00:00' => '03:00', '03:30:00' => '03:30', '04:00:00' => '04:00',
        '04:30:00' => '04:30', '05:00:00' => '05:00', '05:30:00' => '05:30',
        '06:00:00' => '06:00', '06:30:00' => '06:30', '07:00:00' => '07:00',
        '07:30:00' => '07:30', '08:00:00' => '08:00', '08:30:00' => '08:30',
        '09:00:00' => '09:00', '09:30:00' => '09:30', '10:00:00' => '10:00',
        '10:30:00' => '10:30', '11:00:00' => '11:00', '11:30:00' => '11:30',
        '12:00:00' => '12:00', '12:30:00' => '12:30', '13:00:00' => '13:00',
        '13:30:00' => '13:30', '14:00:00' => '14:00', '14:30:00' => '14:30',
        '15:00:00' => '15:00', '15:30:00' => '15:30', '16:00:00' => '16:00',
        '16:30:00' => '16:30', '17:00:00' => '17:00', '17:30:00' => '17:30',
        '18:00:00' => '18:00', '18:30:00' => '18:30', '19:00:00' => '19:00',
        '19:30:00' => '19:30', '20:00:00' => '20:00', '20:30:00' => '20:30',
        '21:00:00' => '21:00', '21:30:00' => '21:30', '22:00:00' => '22:00',
        '22:30:00' => '22:30', '23:00:00' => '23:00', '23:30:00' => '23:30',
        '23:59:00' => '23:59',
    );

    private $naturezas_juridicas = array(
        16 => 'Administração Pública Direta Distrital',
        14 => 'Administração Pública Direta Estadual',
        8 => 'Administração Pública Direta Federal',
        15 => 'Administração Pública Direta Municipal',
        18 => 'Administração Pública Indireta Distrital',
        17 => 'Administração Pública Indireta Estadual',
        10 => 'Administração Pública Indireta Federal',
        19 => 'Administração Pública Indireta Municipal',
        7 => 'Empresa de Economia Mista',
        4 =>  'Empresa privada com fins lucrativos',
        13 => 'Empresa privada sem fins lucrativos',
        6 => 'Empresa Pública',
        12 => 'Organização da Sociedade Civil de Interesse Público',
        11 => 'Organização Social',
        1 => 'Pessoa Física',
        5 => 'Pessoa Jurídica de Natureza Individual',
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

        $this->load->model('espacos_culturais_fotos_m');
        $this->load->model('espacos_culturais_atributos_m');
        $this->load->model('espacos_culturais_horarios_m');
        $this->load->model('atributos_m');

        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('atributos');
        $this->load->helper('permissoes');
        $this->load->helper('extra');

        $this->load->model('espacos_culturais_m');

        $this->config->load('elasticsearch');

        $this->load->library('slug');

        $this->breadcrumbs = array();

        $this->navbar = array();

        $this->layout->set_layout('admin/layout_default');

        $this->load->vars(array(
            'naturezas_juridicas' => $this->naturezas_juridicas,
            'statuses' => $this->statuses,
        ));
    }

    public function index($start = NULL)
    {
        checar_permissao('espacos_culturais.listar_registros');

        $espacos_culturais = $this->espacos_culturais_m->get_all(20, $start);

        $config['base_url'] = site_url('admin/espacos-culturais');
        $config['total_rows'] = $this->espacos_culturais_m->total();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $this->init_breadcrumbs();
        $this->init_navbar('index');

        $dados = array(
            'espacos_culturais' => $espacos_culturais,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
            'pagination' => $this->pagination->create_links(),
        );

        $this->layout->view('admin/espacos_culturais/index', $dados);
    }

    public function create()
    {
        checar_permissao('espacos_culturais.inserir_registro');

        $espaco_cultural = new stdClass();

        $espaco_cultural->fotos = array();
        $espaco_cultural->horarios = $this->listar_horarios_funcionamento();
        $espaco_cultural->horarios_dias = array();

        foreach ($espaco_cultural->horarios as $horario) {
            $espaco_cultural->horarios_dias[$horario->dia_semana] = $horario;
        }

        if ($this->input->post()) {
            if (checar_permissao('espacos_culturais.publicar_registro', NULL)) {
                $this->validation_rules['status']['rules'] .= '|required';
            }

            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $this->slug->set_config(array(
                    'field' => 'slug',
                    'title' => 'nome_espaco',
                    'table' => 'espacos_culturais',
                    'id' => 'id',
                ));

                if (checar_permissao('espacos_culturais.publicar_registro', NULL)) {
                    $status = $this->input->post('status');
                }
                else {
                    $status = 'rascunho';
                }

                $data = array(
                    'status' => $status,
                    'nome_espaco' => $this->input->post('nome_espaco'),
                    'slug' => $this->slug->create_uri($this->input->post('nome_espaco')),
                    'natureza_juridica' => intval($this->input->post('natureza_juridica')),
                    'tipo_espaco' => intval($this->input->post('tipo_espaco')),
                    'nome_responsavel' => $this->input->post('nome_responsavel'),
                    'ponto_cultura' => intval($this->input->post('ponto_cultura')),
                    'area_atuacao_primaria' => intval($this->input->post('area_atuacao_primaria')),
                    'endereco' => $this->input->post('endereco'),
                    'cep' => $this->input->post('cep'),
                    'complemento' => $this->input->post('complemento'),
                    'latitude' => $this->input->post('latitude'),
                    'longitude' => $this->input->post('longitude'),
                    'atividades_culturais' => $this->input->post('atividades_culturais'),
                    'celular' => $this->input->post('celular'),
                    'telefone_fixo' => $this->input->post('telefone_fixo'),
                    'telefone_comercial' => $this->input->post('telefone_comercial'),
                    'site' => $this->input->post('site'),
                    'email' => $this->input->post('email'),
                    'preco_minimo' => floatval($this->input->post('preco_minimo')),
                    'preco_maximo' => floatval($this->input->post('preco_maximo')),
                    'url_agenda_cultural' => $this->input->post('url_agenda_cultural'),
                    'informacoes_preco' => $this->input->post('informacoes_preco'),
                    'fechado_almoco' => $this->input->post('fechado_almoco'),
                    'twitter' => $this->input->post('twitter'),
                    'facebook' => $this->input->post('facebook'),
                    'google_plus' => $this->input->post('google_plus'),
                    'youtube' => $this->input->post('youtube'),
                    'outras_redes_sociais' => $this->input->post('outras_redes_sociais'),
                    'informacoes_adicionais' => $this->input->post('informacoes_adicionais'),
                    'ordem' => $this->espacos_culturais_m->get_proxima_ordem(),
                );

                if($this->input->post('fechado_almoco') == FALSE) {
                    $data['fechado_almoco'] = 1;
                }

                $this->db->trans_start();

                if ($id = $this->espacos_culturais_m->insert($data)) {
                    $espaco_cultural->fotos = $this->listar_arquivos('foto');

                    foreach ($espaco_cultural->fotos as $temp) {
                        $this->espacos_culturais_fotos_m->insert($id, array(
                            'foto_file_id' => $temp->foto_file_id,
                        ));

                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => FALSE,
                        ));
                    }

                    $espaco_fisico_virtual = $this->input->post('espaco_fisico_virtual');

                    if ($espaco_fisico_virtual === FALSE) {
                        $espaco_fisico_virtual = array();
                    }

                    $area_atuacao_cultural = $this->input->post('area_atuacao_cultural');

                    if ($area_atuacao_cultural === FALSE) {
                        $area_atuacao_cultural = array();
                    }

                    $formas_pagamento = $this->input->post('formas_pagamento');

                    if ($formas_pagamento === FALSE) {
                        $formas_pagamento = array();
                    }

                    $espaco_cultural->atributos = array_merge(
                        $espaco_fisico_virtual,
                        $area_atuacao_cultural,
                        $formas_pagamento
                    );

                    foreach ($espaco_cultural->atributos as $temp) {
                        $this->espacos_culturais_atributos_m->insert($id, array(
                            'atributo_id' => $temp,
                        ));
                    }

                    foreach ($espaco_cultural->horarios as $horario) {
                        $this->espacos_culturais_horarios_m->insert($id, array(
                            'dia_semana' => $horario->dia_semana,
                            'horario_abertura' => $horario->horario_abertura,
                            'horario_fechamento' => $horario->horario_fechamento,
                            'fechado' => intval($horario->fechado),
                        ));
                    }

                    $this->db->trans_complete();
                    $this->indexar_espaco($id, $data);
                    $this->session->set_flashdata('success', 'Espaço Cultural adicionado com sucesso.');
                    redirect('admin/espacos-culturais');
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar espaço cultural.');
                    redirect('admin/espacos-culturais');
                }
            }
        }

        foreach ($this->validation_rules as $rule) {
            $espaco_cultural->{$rule['field']} = $this->input->post($rule['field']);
        }

        $this->init_breadcrumbs();

        $this->breadcrumbs[] = array('Novo', site_url("admin/espacos-culturais/create"));

        $this->init_navbar('create');

        $dados = array(
            'horarios' => $this->horarios,
            'tipos_espacos' => $this->atributos_m->get_slug('tipo-do-espaco', TRUE),
            'tipos_espacos_selecionados' => $this->listar_tipos_espacos(),
            'areas_atuacao_primaria' => $this->atributos_m->get_slug('area-de-atuacao-cultural', TRUE),
            'espacos_fisicos_virtuais' => $this->atributos_m->get_slug('espaco-fisico-virtual', TRUE),
            'espacos_fisicos_virtuais_selecionados' => (array) $this->input->post('espaco_fisico_virtual'),
            'pontos_cultura' => $this->atributos_m->get_slug('ponto-de-cultura', TRUE),
            'pontos_cultura_selecionados' => $this->listar_pontos_cultura(),
            'areas_atuacoes_culturais' => $this->atributos_m->get_slug('area-de-atuacao-cultural', TRUE),
            'areas_atuacoes_culturais_selecionadas' => (array) $this->input->post('area_atuacao_cultural'),
            'formas_pagamentos' => $this->atributos_m->get_slug('formas-de-pagamento', TRUE),
            'formas_pagamentos_selecionadas' => (array) $this->input->post('formas_pagamento'),
            'espaco_cultural' => $espaco_cultural,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/espacos_culturais/form', $dados);
    }

    public function edit($id)
    {
        checar_permissao('espacos_culturais.modificar_registro');

        $espaco_cultural = $this->espacos_culturais_m->get($id);

        if (! $espaco_cultural)
            show_404();

        $espaco_cultural->fotos = $this->espacos_culturais_fotos_m->get_all_by_espaco_cultural($espaco_cultural->id);
        $espaco_cultural->horarios = $this->espacos_culturais_horarios_m->get_all_by_espaco_cultural($espaco_cultural->id);
        $espaco_cultural->horarios_dias = array();

        foreach ($espaco_cultural->horarios as $horario) {
            $espaco_cultural->horarios_dias[$horario->dia_semana] = $horario;
        }

        $espaco_cultural->atributos = $this->espacos_culturais_atributos_m->get_all_by_espaco_cultural($espaco_cultural->id);

        if ($this->input->post()) {
            $this->form_validation->set_rules($this->validation_rules);

            if ($this->form_validation->run()) {
                $this->slug->set_config(array(
                    'field' => 'slug',
                    'title' => 'nome_espaco',
                    'table' => 'espacos_culturais',
                    'id' => 'id',
                ));

                if (checar_permissao('espacos_culturais.publicar_registro', NULL)) {
                    $status = $this->input->post('status');
                }
                else {
                    $status = 'rascunho';
                }

                $data = array(
                    'status' => $status,
                    'nome_espaco' => $this->input->post('nome_espaco'),
                    'slug' => $this->slug->create_uri($this->input->post('nome_espaco'), $id),
                    'natureza_juridica' => intval($this->input->post('natureza_juridica')),
                    'tipo_espaco' => intval($this->input->post('tipo_espaco')),
                    'nome_responsavel' => $this->input->post('nome_responsavel'),
                    'ponto_cultura' => intval($this->input->post('ponto_cultura')),
                    'area_atuacao_primaria' => intval($this->input->post('area_atuacao_primaria')),
                    'endereco' => $this->input->post('endereco'),
                    'cep' => $this->input->post('cep'),
                    'complemento' => $this->input->post('complemento'),
                    'latitude' => $this->input->post('latitude'),
                    'longitude' => $this->input->post('longitude'),
                    'atividades_culturais' => $this->input->post('atividades_culturais'),
                    'celular' => $this->input->post('celular'),
                    'telefone_fixo' => $this->input->post('telefone_fixo'),
                    'telefone_comercial' => $this->input->post('telefone_comercial'),
                    'site' => $this->input->post('site'),
                    'email' => $this->input->post('email'),
                    'preco_minimo' => floatval($this->input->post('preco_minimo')),
                    'preco_maximo' => floatval($this->input->post('preco_maximo')),
                    'url_agenda_cultural' => $this->input->post('url_agenda_cultural'),
                    'informacoes_preco' => $this->input->post('informacoes_preco'),
                    'fechado_almoco' => $this->input->post('fechado_almoco'),
                    'twitter' => $this->input->post('twitter'),
                    'facebook' => $this->input->post('facebook'),
                    'google_plus' => $this->input->post('google_plus'),
                    'youtube' => $this->input->post('youtube'),
                    'outras_redes_sociais' => $this->input->post('outras_redes_sociais'),
                    'informacoes_adicionais' => $this->input->post('informacoes_adicionais'),
                );

                $this->db->trans_start();

                if ($this->espacos_culturais_m->update($id, $data)) {
                    $fotos_old = $espaco_cultural->fotos;
                    $espaco_cultural->fotos = $this->listar_arquivos('foto');

                    foreach ($fotos_old as $temp) {
                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => TRUE,
                        ));
                    }

                    $this->espacos_culturais_fotos_m->delete_all_by_espaco_cultural($espaco_cultural->id);

                    foreach ($espaco_cultural->fotos as $temp) {
                        $this->espacos_culturais_fotos_m->insert($id, array(
                            'foto_file_id' => $temp->foto_file_id,
                        ));

                        $this->arquivos_m->update($temp->foto_file_id, array(
                            'temp' => FALSE,
                        ));
                    }

                    $this->espacos_culturais_atributos_m->delete_all_by_espaco_cultural($espaco_cultural->id);

                    $espaco_fisico_virtual = $this->input->post('espaco_fisico_virtual');

                    if ($espaco_fisico_virtual === FALSE) {
                        $espaco_fisico_virtual = array();
                    }

                    $area_atuacao_cultural = $this->input->post('area_atuacao_cultural');

                    if ($area_atuacao_cultural === FALSE) {
                        $area_atuacao_cultural = array();
                    }

                    $formas_pagamento = $this->input->post('formas_pagamento');

                    if ($formas_pagamento === FALSE) {
                        $formas_pagamento = array();
                    }

                    $espaco_cultural->atributos = array_merge(
                        $espaco_fisico_virtual,
                        $area_atuacao_cultural,
                        $formas_pagamento
                    );

                    foreach ($espaco_cultural->atributos as $temp) {
                        $this->espacos_culturais_atributos_m->insert($id, array(
                            'atributo_id' => $temp,
                        ));
                    }

                    $this->espacos_culturais_horarios_m->delete_all_by_espaco_cultural($espaco_cultural->id);
                    $espaco_cultural->horarios = $this->listar_horarios_funcionamento();
                    $espaco_cultural->horarios_dias = array();

                    foreach ($espaco_cultural->horarios as $horario) {
                        $espaco_cultural->horarios_dias[$horario->dia_semana] = $horario;
                    }

                    foreach ($espaco_cultural->horarios as $horario) {
                        $this->espacos_culturais_horarios_m->insert($id, array(
                            'dia_semana' => $horario->dia_semana,
                            'horario_abertura' => $horario->horario_abertura,
                            'horario_fechamento' => $horario->horario_fechamento,
                            'fechado' => intval($horario->fechado),
                        ));
                    }

                    $this->db->trans_complete();
                    $this->reindexar_espaco($id, $data);
                    $this->session->set_flashdata('success', 'Espaço Cultural atualizado com sucesso.');
                    redirect('admin/espacos-culturais');
                }
                else {
                    $this->db->trans_complete();
                    $this->session->set_flashdata('error', 'Erro ao atualizar espaço cultural.');
                    redirect('admin/espacos-culturais');
                }
            }

            foreach ($this->validation_rules as $rule) {
                $espaco_cultural->{$rule['field']} = $this->input->post($rule['field']);
            }
        }

        $this->init_breadcrumbs();

        $this->breadcrumbs[] = array('Editar', site_url("admin/espacos-culturais/edit/$id"));

        $this->init_navbar('edit', $id);

        $tipos_espacos_selecionados = $this->listar_tipos_espacos();

        if (! count($tipos_espacos_selecionados) && $espaco_cultural->tipo_espaco) {
            $tipos_espacos_selecionados = array_merge(array($espaco_cultural->tipo_espaco),
                    $this->atributos_m->get_all_ascendentes($espaco_cultural->tipo_espaco, TRUE));
        }

        $espacos_fisicos_virtuais_selecionados = $this->input->post('espaco_fisico_virtual');

        if ($espacos_fisicos_virtuais_selecionados === FALSE) {
            $espacos_fisicos_virtuais_selecionados = array();

            foreach ($espaco_cultural->atributos as $atributo) {
                $espacos_fisicos_virtuais_selecionados[] = $atributo->atributo_id;
            }
        }

        $areas_atuacoes_culturais_selecionadas = $this->input->post('area_atuacao_cultural');

        if ($areas_atuacoes_culturais_selecionadas === FALSE) {
            $areas_atuacoes_culturais_selecionadas = array();

            foreach ($espaco_cultural->atributos as $atributo) {
                $areas_atuacoes_culturais_selecionadas[] = $atributo->atributo_id;
            }
        }

        $pontos_cultura_selecionados = $this->listar_pontos_cultura();

        if (! count($pontos_cultura_selecionados) && $espaco_cultural->ponto_cultura) {
            $pontos_cultura_selecionados = array_merge(array($espaco_cultural->ponto_cultura),
                    $this->atributos_m->get_all_ascendentes($espaco_cultural->ponto_cultura, TRUE));
        }

        $formas_pagamentos_selecionadas = $this->input->post('formas_pagamento');

        if ($formas_pagamentos_selecionadas === FALSE) {
            $formas_pagamentos_selecionadas = array();

            foreach ($espaco_cultural->atributos as $atributo) {
                $formas_pagamentos_selecionadas[] = $atributo->atributo_id;
            }
        }

        $dados = array(
            'horarios' => $this->horarios,
            'tipos_espacos' => $this->atributos_m->get_slug('tipo-do-espaco', TRUE),
            'tipos_espacos_selecionados' => $tipos_espacos_selecionados,
            'espacos_fisicos_virtuais' => $this->atributos_m->get_slug('espaco-fisico-virtual', TRUE),
            'espacos_fisicos_virtuais_selecionados' => $espacos_fisicos_virtuais_selecionados,
            'pontos_cultura' => $this->atributos_m->get_slug('ponto-de-cultura', TRUE),
            'pontos_cultura_selecionados' => $pontos_cultura_selecionados,
            'areas_atuacao_primaria' => $this->atributos_m->get_slug('area-de-atuacao-cultural', TRUE),
            'area_atuacao_primaria_selecionada' => $espaco_cultural->area_atuacao_primaria,
            'areas_atuacoes_culturais' => $this->atributos_m->get_slug('area-de-atuacao-cultural', TRUE),
            'areas_atuacoes_culturais_selecionadas' => $areas_atuacoes_culturais_selecionadas,
            'formas_pagamentos' => $this->atributos_m->get_slug('formas-de-pagamento', TRUE),
            'formas_pagamentos_selecionadas' => $formas_pagamentos_selecionadas,
            'espaco_cultural' => $espaco_cultural,
            'breadcrumbs' => $this->breadcrumbs,
            'navbar' => $this->navbar,
        );

        $this->layout->view('admin/espacos_culturais/form', $dados);
    }

    public function sort($id, $dir)
    {
        checar_permissao('espacos_culturais.modificar_registro');

        $espaco_cultural = $this->espacos_culturais_m->get($id) or show_404();

        $espacos_culturais = $this->espacos_culturais_m->get_all();
        $curr_idx = -1;

        for ($i = 0; $i < count($espacos_culturais); $i++) {
            if ($espacos_culturais[$i]->id == $id) {
                $curr_idx = $i;

                break;
            }
        }

        if ($curr_idx != -1) {
            $new_idx = $curr_idx + (intval($dir) * 1);

            if (($new_idx >= 0) && ($new_idx < count($espacos_culturais))) {
                $temp = $espacos_culturais[$new_idx];
                $espacos_culturais[$new_idx] = $espacos_culturais[$curr_idx];
                $espacos_culturais[$curr_idx] = $temp;
            }
        }

        $this->db->trans_start();

        for ($i = 0; $i < count($espacos_culturais); $i++) {
            $this->espacos_culturais_m
                    ->update($espacos_culturais[$i]->id, array('ordem' => $i + 1));
        }

        $this->db->trans_complete();

        redirect('admin/espacos-culturais');
    }

    public function delete($id)
    {
        checar_permissao('espacos_culturais.excluir_registro');

        $espaco_cultural = $this->espacos_culturais_m->get($id) or show_404();

        $this->db->trans_start();

        if ($this->espacos_culturais_m->delete($espaco_cultural->id)) {
            $this->db->trans_complete();
            $this->desindexar_espaco($espaco_cultural->id);
            $this->session->set_flashdata('success', 'Espaço Cultural excluído com sucesso.');
            redirect('admin/espacos-culturais');
        }
        else {
            $this->session->set_flashdata('error', 'Erro ao excluir espaço cultural.');
            redirect('admin/espacos-culturais');
        }
    }

    public function delete_many()
    {
        checar_permissao('espacos_culturais.excluir_registro');

        $espacos_culturais = (array) $this->input->post('espacos_culturais');

        foreach ($espacos_culturais as $espaco_cultural_id) {
            $espaco_cultural = $this->espacos_culturais_m->get($espaco_cultural_id);

            if (! $espaco_cultural)
                continue;

            $this->db->trans_start();
            $this->espacos_culturais_m->delete($espaco_cultural->id);
            $this->db->trans_complete();
            $this->desindexar_espaco($espaco_cultural->id);
        }

        $this->session->set_flashdata('success', 'Espaço Cultural(s) excluído(s) com sucesso.');
        redirect('admin/espacos-culturais');
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
        $this->breadcrumbs[] = array('Listagem de espaços culturais',
                site_url("admin/espacos-culturais"));
    }

    private function init_navbar($method, $id = NULL)
    {
        if ($method == 'index') {
            $this->navbar[] =  '<li class="pull-right"><a href="' . site_url("admin/espacos-culturais/create")
                    . '" class="btn btn-small btn-success"><i class="icon-white icon-plus"></i> Espaço Cultural</a></li>';
        }
        else if ($method == 'edit') {
            $this->navbar[] =  '<li class="pull-right"><a href="' . site_url("admin/espacos-culturais/{$id}/eventos")
                    . '" class="btn btn-small btn-info">Eventos</a></li>';
        }
    }

    private function listar_tipos_espacos()
    {
        $tipos_espacos = array();
        $nivel = 1;

        while ($tipo_espaco = $this->input->post("tipo_espaco_{$nivel}")) {
            $tipos_espacos[] = $tipo_espaco;
            $nivel++;
        }

        if ($this->input->post('tipo_espaco') !== FALSE) {
            $tipos_espacos[] = $this->input->post('tipo_espaco');
        }

        return $tipos_espacos;
    }

    private function listar_pontos_cultura()
    {
        $pontos_cultura = array();
        $nivel = 1;

        while ($ponto_cultura = $this->input->post("ponto_cultura_{$nivel}")) {
            $pontos_cultura[] = $ponto_cultura;
            $nivel++;
        }

        if ($this->input->post('ponto_cultura') !== FALSE) {
            $pontos_cultura[] = $this->input->post('ponto_cultura');
        }

        return $pontos_cultura;
    }

    private function listar_horarios_funcionamento()
    {
        $dia_semana = $this->input->post('horarios_dia_semana');

        if ($dia_semana === FALSE) {
            return array();
        }

        $dia_semana = (array) $dia_semana;
        $horario_abertura = (array) $this->input->post('horarios_horario_abertura');
        $horario_fechamento = (array) $this->input->post('horarios_horario_fechamento');
        $horarios = array();

        for ($i = 0; $i < 7; $i++) {
            $horario = new stdClass();
            $horario->dia_semana = (int) @$dia_semana[$i];
            $horario->horario_abertura = @$horario_abertura[$i];

            if (! preg_match('/[0-9]{2}:[0-9]{2}/', $horario->horario_abertura)) {
                $horario->horario_abertura = NULL;
            }

            $horario->horario_fechamento = @$horario_fechamento[$i];

            if (! preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $horario->horario_fechamento)) {
                $horario->horario_fechamento = NULL;
            }

            $horario->fechado = (bool) intval($this->input->post("horarios_fechado_{$i}"));
            $horarios[] = $horario;
        }

        return $horarios;
    }

    public function _checar_area_atuacao_cultural($area_atuacao_cultural)
    {
        if (! $area_atuacao_cultural || ! count($area_atuacao_cultural)) {
            $this->form_validation->set_message('_checar_area_atuacao_cultural', 'É obrigatória a seleção de pelo menos uma Área de atuação cultural');

            return FALSE;

        }
        else {
            return TRUE;
        }
    }

    private function indexar_espaco($id, $dados_espaco)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->index(array(
            'body' => array(
                'nome_espaco' => $dados_espaco['nome_espaco'],
                'slug' => $dados_espaco['slug'],
                'atividades_culturais' => strip_tags_better($dados_espaco['atividades_culturais']),
            ),

            'index' => 'varadouro',

            'type' => 'espaco_cultural',

            'id' => $id,
        ));
    }

    private function reindexar_espaco($id, $dados_espaco)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->update(array(
            'body' => array(
                'doc' => array(
                    'nome_espaco' => $dados_espaco['nome_espaco'],
                    'slug' => $dados_espaco['slug'],
                    'atividades_culturais' => strip_tags_better($dados_espaco['atividades_culturais']),
                ),
            ),

            'index' => 'varadouro',

            'type' => 'espaco_cultural',

            'id' => $id,
        ));
    }

    private function desindexar_espaco($id)
    {
        $client_params = array(
            'hosts' => $this->config->item('elasticsearch_hosts'),
        );

        $client = new Elasticsearch\Client($client_params);

        $client->delete(array(
            'index' => 'varadouro',

            'type' => 'espaco_cultural',

            'id' => $id,
        ));
    }

}
