<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

    private $senha_gerada = NULL;

    private $usuario = NULL;

    private $safe_tags = '<strong><i><b><em><a><br>';

    private $safe_tags_2 = '<strong><i><b><em><a><br><p>';

    private $meses = array(
        'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
        'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro',
    );

    private $dias_semana = array(
        'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo'
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

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->lang->load('form_validation');
        $this->load->library('arquivos');
        $this->load->model('arquivos_m');
        $this->load->model('usuarios_m');
        $this->load->model('perfis_site_m');
        $this->load->model('sobre_m');
        $this->load->model('projetos_m');
        $this->load->model('projetos_imagens_m');

        $this->load->helper('text');
        $this->load->helper('extra');
        $this->load->model('noticias_m');
        $this->load->model('noticias_fotos_m');

        $this->load->model('espacos_culturais_m');
        $this->load->model('agentes_culturais_m');
        $this->load->model('espacos_culturais_atributos_m');
        $this->load->model('agentes_culturais_atributos_m');
        $this->load->model('atributos_m');

        $this->load->model('eventos_m');
        $this->load->model('eventos_fotos_m');

        $this->load->model('usuarios_agenda_m');

        $this->load->model('busca_m');

        $this->config->load('elasticsearch');

        $temp = $this->session->userdata('usuario_site');

        if ($temp)
            $this->usuario = $this->perfis_site_m->get_by_usuario($temp->id);

        $this->load->vars(array(
            'safe_tags' => $this->safe_tags,
            'safe_tags2' => $this->safe_tags_2,
            'usuario' => $this->usuario,
            'meses' => $this->meses,
            'dias_semana' => $this->dias_semana,
            'naturezas_juridicas' => $this->naturezas_juridicas,
        ));
    }

    public function index()
    {
        $eventos_destaque = $this->eventos_m->get_all(3, NULL, TRUE);

        foreach ($eventos_destaque as $evento)
        {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        }

        $areas_primarias = $this->atributos_m->get_slug('area-de-atuacao-cultural', TRUE);
        $areas_primarias = $areas_primarias->itens;
        usort($areas_primarias, function($obj1, $obj2){
            return strcmp($obj1->nome, $obj2->nome);
        });

        $tipos_espacos = $this->atributos_m->get_slug('tipo-do-espaco', TRUE);
        $tipos_espacos = $tipos_espacos->itens;
        usort($tipos_espacos, function($obj1, $obj2){
            return strcmp($obj1->nome, $obj2->nome);
        });

        $noticias = $this->noticias_m->get_all_with_img(6);

        $espacos = $this->espacos_culturais_m->get_all(NULL, NULL, TRUE);
        $agentes = $this->agentes_culturais_m->get_all(NULL, NULL, TRUE);

        $dados = array(
            'layout_namespace' => 'home',
            'main_class' => 'home',
            'eventos_destaque' => $eventos_destaque,
            'noticias' => $noticias,
            'areas_primarias' => $areas_primarias,
            'tipos_espacos' => $tipos_espacos,
            'espacos' => $espacos,
            'agentes' => $agentes,
            'title' => 'Varadouro Cultural',
            'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
        );

        $this->layout->view('home', $dados);
    }

    public function agenda($ano = NULL, $mes = NULL, $dia = NULL)
    {
        if($ano) {
            if($mes) {
                if($dia) {
                    $eventos = $this->eventos_m->get_all_by_date($ano, $mes, $dia, 11, NULL, TRUE);
                }
            }
        }
        else {
            $eventos = $this->eventos_m->get_all(11, NULL, TRUE);
        }

        foreach ($eventos as $evento)
        {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        }

        if($eventos) {
            $evento_primario = $eventos[0];
            unset($eventos[0]);
        } else {
            $evento_primario = $eventos;
        }

        $dados = array(
            'layout_namespace' => 'location',
            'eventos' => $eventos,
            'evento_primario' => $evento_primario,
            'start' => count($eventos) - 2 + 1 + count($evento_primario),
            'has_more' => (count($eventos) > 9),
            'title' => 'Varadouro Cultural - Agenda Cultural',
            'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
        );

        $this->layout->view('agenda', $dados);
    }

    public function agentes($id_or_slug)
    {
        $this->load->model('agentes_culturais_fotos_m');
        $this->load->model('agentes_culturais_horarios_m');
        $this->load->model('agentes_culturais_atributos_m');
        $this->load->model('atributos_m');

        $agente = $this->agentes_culturais_m->get_by_slug(urldecode($id_or_slug), TRUE);

        if (! $agente && is_numeric($id_or_slug)) {
            $agente = $this->agentes_culturais_m->get($id_or_slug, TRUE);
        }

        if(! $agente)
            show_404();

        $agente->fotos = $this->agentes_culturais_fotos_m->get_all_by_agente_cultural($agente->id);
        $agente->horario = $this->agentes_culturais_horarios_m->get_all_by_agente_cultural($agente->id);
        $agente->area_primaria = $this->atributos_m->get($agente->area_atuacao_primaria);
        $agente->eventos_relacionados = $this->eventos_m->get_all_by_agente_cultural($agente->id, 3, TRUE);
        $agente->anterior = $this->agentes_culturais_m->get_agente_anterior($agente->id, TRUE);
        $agente->proximo = $this->agentes_culturais_m->get_agente_proximo($agente->id, TRUE);

        foreach ($agente->eventos_relacionados as $evento) {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
            $evento->nome_espaco = $evento->espaco->nome_espaco;
        }

        $slug = 'area-de-atuacao-cultural';

        $agente->filtros = $this->agentes_culturais_atributos_m->get_first_children($agente->id, $slug);

        $noticias = $this->noticias_m->get_all_with_img(3);

        $dados = array(
            'layout_namespace' => 'location',
            'agente' => $agente,
            'noticias' => $noticias,
            'title' => 'Varadouro Cultural - Agente: '.$agente->nome_responsavel,
            'meta_description' => substr(strip_tags($agente->atividades_culturais), 0, 150),
        );

        $this->layout->view('agentes', $dados);
    }

    public function busca()
    {

        $q = trim(urldecode((string) $this->input->get_post('q')));

        $qdata = array(
            'q' => urlencode($q),
        );

        $results_temp = $this->busca_m->buscar($q, array('evento', 'noticia', 'espaco_cultural', 'agente_cultural'), 6);
        $hits = $results_temp['hits'];
        $itens = array();

        foreach ($hits['hits'] as $temp) {
            $data = $temp['_source'];
            $item = new stdClass();

            if($temp['_type'] == 'evento') {
                $item->nome = $data['titulo'];
                $item->tipo = $temp['_type'];
                $item->href = '/evento/'.$data['slug'];
                $item->data = $data['data'];
            }
            else if ($temp['_type'] == 'noticia') {
                $item->nome = $data['titulo'];
                $item->tipo = $temp['_type'];
                $item->href = '/noticias/'.$data['slug'];
                $item->data = $data['data'];
            }
            else if ($temp['_type'] == 'espaco_cultural') {
                $item->nome = $data['nome_espaco'];
                $item->tipo = $temp['_type'];
                $item->href = '/espacos/'.$data['slug'];
            }
            else if ($temp['_type'] == 'agente_cultural') {
                $item->nome = $data['nome_responsavel'];
                $item->tipo = $temp['_type'];
                $item->href = '/agentes/'.$data['slug'];
            }

            $itens[] = $item;
        }

        $qs = '?' . http_build_query($qdata);

        $dados = array(
            'layout_namespace' => 'home',
            'q' => $q,
            'itens' => $itens,
            'start' => count($itens) - 2 + 1,
            'has_more' => (count($itens) > 5),
        );

        $this->layout->view('busca', $dados);
    }

    public function contato()
    {
        $erros = '';
        $enviado = FALSE;

        $dados_contato = new stdClass();

        if ($this->input->post()) {
            $validation_rules = array(
                array(
                    'field' => 'name',
                    'label' => 'Nome',
                    'rules' => 'trim|required',
                ),

                array(
                    'field' => 'email',
                    'label' => 'E-mail',
                    'rules' => 'trim|required',
                ),

                array(
                    'field' => 'phone',
                    'label' => 'Telefone',
                    'rules' => 'trim|required',
                ),

                array(
                    'field' => 'message',
                    'label' => 'Mensagem',
                    'rules' => 'trim|required',
                ),
            );

            $this->load->library('form_validation');
            $this->lang->load('form_validation');
            $this->form_validation->set_rules($validation_rules);

            $dados_contato->name = (string) $this->input->post('name');
            $dados_contato->email = (string) $this->input->post('email');
            $dados_contato->phone = (string) $this->input->post('phone');
            $dados_contato->message = (string) $this->input->post('message');

            if ($this->form_validation->run()) {

                $this->load->library('email');
                $this->email->set_mailtype('html');

                $this->email->from($dados_contato->email);
                $this->email->to('contato@varadourocultural.org');

                $this->email->subject('Varadouro Cultural: Contato');

                $msg = 'Nome: ' . $dados_contato->name . '<br><br>'.
                    'Telefone: ' . $dados_contato->phone . '<br><br>'.
                    'Mensagem: ' . $dados_contato->message;

                $this->email->message($msg);
                $this->email->send();

                $enviado = TRUE;

                $dados_contato = new stdClass();
                $dados_contato->body = NULL;
            }
            else {
                $erros .= validation_errors();
            }
        }

        $dados = array(
            'layout_class' => 'contact',
            'dados_contato' => $dados_contato,
            'erros' => $erros,
            'enviado' => $enviado,
            'title' => 'Varadouro Cultural - Contato',
            'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
        );

        $this->layout->view('contato', $dados);
    }

    public function espacos($id_or_slug = NULL)
    {
        $this->load->model('espacos_culturais_fotos_m');
        $this->load->model('espacos_culturais_horarios_m');
        $this->load->model('espacos_culturais_atributos_m');
        $this->load->model('atributos_m');

        $espaco = $this->espacos_culturais_m->get_by_slug(urldecode($id_or_slug), TRUE);

        if (! $espaco && is_numeric($id_or_slug)) {
            $espaco = $this->espacos_culturais_m->get($id_or_slug, TRUE);
        }

        if(! $espaco)
            show_404();

        $espaco->filtros = array();

        $espaco->fotos = $this->espacos_culturais_fotos_m->get_all_by_espaco_cultural($espaco->id);
        $espaco->horario = $this->espacos_culturais_horarios_m->get_all_by_espaco_cultural($espaco->id);
        $espaco->area_primaria = $this->atributos_m->get($espaco->area_atuacao_primaria);
        $espaco->anterior = $this->espacos_culturais_m->get_espaco_anterior($espaco->id, TRUE);
        $espaco->proximo = $this->espacos_culturais_m->get_espaco_proximo($espaco->id, TRUE);

        $slug = 'area-de-atuacao-cultural';

        $espaco->filtros = $this->espacos_culturais_atributos_m->get_first_children($espaco->id, $slug);
        array_push($espaco->filtros, $this->atributos_m->get($espaco->tipo_espaco));

        $eventos_relacionados = $this->eventos_m->get_all_by_espaco_cultural($espaco->id, 3, NULL, TRUE);

        $noticias = $this->noticias_m->get_all_with_img(3);

        $dados = array(
            'layout_namespace' => 'location',
            'espaco' => $espaco,
            'eventos_relacionados' => $eventos_relacionados,
            'noticias' => $noticias,
            'title' => 'Varadouro Cultural - Espaço: '.$espaco->nome_espaco,
            'meta_description' => substr(strip_tags($espaco->atividades_culturais), 0, 150),
        );

        $this->layout->view('espacos', $dados);
    }

    public function espacos_agentes()
    {

        $espacos = $this->espacos_culturais_m->get_all_with_img(5, NULL, array(), TRUE);

        foreach ($espacos as $espaco)
        {
            $espaco->filtros = array();

            $slug = 'area-de-atuacao-cultural';

            $espaco->area_primaria = $this->atributos_m->get($espaco->area_atuacao_primaria);
            $espaco->filtros = $this->espacos_culturais_atributos_m->get_first_children($espaco->id, $slug, 4);
            array_push($espaco->filtros, $this->atributos_m->get($espaco->tipo_espaco));

            $espaco->filtros_str = '';

            $num_filtros = count($espaco->filtros);

            foreach ($espaco->filtros as $filtro)
            {
                if($filtro == $espaco->filtros[$num_filtros - 1])
                {
                    $espaco->filtros_str .= $filtro->nome;
                } else
                {
                    $espaco->filtros_str .= $filtro->nome.', ';
                }
            }
        }

        $agentes = $this->agentes_culturais_m->get_all_with_img(3, NULL, array(), TRUE);

        foreach ($agentes as $agente)
        {
            $agente->filtros = array();

            $slug = 'area-de-atuacao-cultural';

            $agente->area_primaria = $this->atributos_m->get($agente->area_atuacao_primaria);
            $agente->filtros = $this->agentes_culturais_atributos_m->get_first_children($agente->id, $slug, 5);

            $agente->filtros_str = '';

            $num_filtros = count($agente->filtros);

            foreach ($agente->filtros as $filtro)
            {
                if($filtro == $agente->filtros[$num_filtros - 1])
                {
                    $agente->filtros_str .= $filtro->nome;
                } else
                {
                    $agente->filtros_str .= $filtro->nome.', ';
                }
            }
        }

        $dados = array(
            'layout_namespace' => 'home',
            'espacos' => $espacos,
            'agentes' => $agentes,
            'has_more' => ((count($agentes) > 2) || (count($espacos) > 4)),
            'has_more_agentes'=> (count($agentes) > 2),
            'has_more_espacos' => (count($espacos) > 4),
            'start_agentes' => count($agentes) - 2 + 1,
            'start_espacos' => count($espacos) - 2 + 1,
            'title' => 'Varadouro Cultural - Espaços e Agentes',
            'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
        );

        $this->layout->view('espacos_agentes', $dados);
    }

    public function evento($id_or_slug)
    {
        $this->load->model('espacos_culturais_horarios_m');
        $this->load->model('agentes_culturais_eventos_m');

        $evento = $this->eventos_m->get_by_slug(urldecode($id_or_slug), TRUE);

        if (! $evento && is_numeric($id_or_slug)) {
            $evento = $this->eventos_m->get($id_or_slug, TRUE);
        }

        if(! $evento)
            show_404();

        $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        $evento->espaco->horario = $this->espacos_culturais_horarios_m->get_all_by_espaco_cultural($evento->espaco->id);
        $evento->fotos = $this->eventos_fotos_m->get_all_by_evento($evento->id);
        $evento->anterior = $this->eventos_m->get_evento_anterior($evento->id, TRUE);
        $evento->proximo = $this->eventos_m->get_evento_proximo($evento->id, TRUE);

        if($u = $this->usuario)
            $evento->adicionado = $this->usuarios_agenda_m->search($u->id, $evento->id);


        $agentes_eventos = $this->agentes_culturais_eventos_m->get_all_by_evento($evento->id);
        $agentes = array();

        foreach ($agentes_eventos as $agente) {
            array_push($agentes, $this->agentes_culturais_m->get($agente->agente_cultural_id, TRUE));
        }

        $dados = array(
            'layout_namespace' => 'event',
            'evento' => $evento,
            'agentes' =>$agentes,
            'title' => 'Varadouro Cultural - Evento: '.$evento->titulo,
            'meta_description' => substr(strip_tags($evento->descricao), 0, 150),
        );

        $this->layout->view('evento', $dados);
    }

    public function login()
    {
        if($this->get_user_info())
            redirect('/');

        $dados = array(
                'erro' => '',
                'title' => 'Varadouro Cultural - Login',
                'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
            );

        if($this->input->post()) {

            if ($this->login_usuario()) {
                $usuario = $this->usuarios_m->get($this->usuario->id);
                $perfil = $this->perfis_site_m->get_by_usuario($this->usuario->id);

                $dados['usuario'] = $usuario;
                redirect('usuario/'.urlencode($perfil->username));
            }
            else {
                $erro = 'Nome de usuário ou senha incorreto.';
                $dados['erro'] = $erro;
            }
        }

        $this->layout->view('login', $dados);
    }

    public function noticias()
    {
        $noticias = $this->noticias_m->get_all_with_img(12, NULL);

        $dados = array(
            'layout_namespace' => 'location',
            'noticias' => $noticias,
            'start' => count($noticias) - 2 + 1,
            'has_more' => (count($noticias) > 11),
            'title' => 'Notícias do Varadouro Cultural',
            'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
        );

        $this->layout->view('noticias', $dados);
    }

    public function noticias_post($id_or_slug)
    {
        $noticia = $this->noticias_m->get_by_slug(urldecode($id_or_slug));

        if (! $noticia && is_numeric($id_or_slug)) {
            $noticia = $this->noticias_m->get($id_or_slug);
        }

        if(! $noticia)
            show_404();

        $noticia->fotos = $this->noticias_fotos_m->get_all_by_noticia($noticia->id);

        $mais_noticias = $this->noticias_m->get_all_with_img(3, NULL, array($noticia->id));

        $dados = array(
            'layout_namespace' => 'location',
            'noticia' => $noticia,
            'mais_noticias' => $mais_noticias,
            'title' => 'Varadouro Cultural - Notícia: '.$noticia->titulo,
            'meta_description' => substr(strip_tags($noticia->conteudo), 0, 150),
        );

        $this->layout->view('noticias_post', $dados);
    }

    public function usuario_agenda($id_or_username)
    {
        $this->load->model('espacos_culturais_m');

        $usuario = $this->get_by_username(urldecode($id_or_username));

        if (! $usuario && is_numeric($id_or_username)) {
            $usuario = $this->usuarios_m->get($id_or_username);
        }

        if(! $usuario)
            show_404();

        $perfil = $this->perfis_site_m->get_by_usuario($usuario->id);

        if (! $perfil)
            show_404();

        if ($u = $this->session->userdata('usuario_site')) {
            if (($u->id != $usuario->id) && ($perfil->agenda_publica == 0)) {
                redirect('/');
            }
        } else if ($perfil->agenda_publica == 0) {
            redirect('/');
        }

        $usuario->username = $perfil->username;
        $usuario->sobrenome = $perfil->sobrenome;
        $usuario->agenda_publica = $perfil->agenda_publica;
        $usuario->avatar = $this->arquivos->obter($perfil->avatar_file_id);

        $eventos = $this->usuarios_agenda_m->get_all_by_usuario($usuario->id, 10);

        foreach ($eventos as $evento) {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        }

        $dados = array(
                'usuario' => $usuario,
                'eventos' => $eventos,
                'start' => count($eventos) - 2 + 1,
                'has_more' => (count($eventos) > 9),
                'title' => 'Varadouro Cultural - Agenda: '.$usuario->nome,
                'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
            );

        $this->layout->view('usuario_agenda', $dados);
    }

    public function usuario_agenda_pdf($id_or_username)
    {
        $this->load->model('espacos_culturais_m');

        $usuario = $this->get_by_username(urldecode($id_or_username));

        if (! $usuario && is_numeric($id_or_username)) {
            $usuario = $this->usuarios_m->get($id_or_username);
        }

        if(! $usuario)
            show_404();

        $perfil = $this->perfis_site_m->get_by_usuario($usuario->id);

        if (! $perfil)
            show_404();

        if ($u = $this->session->userdata('usuario_site')) {
            if (($u->id != $usuario->id) && ($perfil->agenda_publica == 0)) {
                redirect('/');
            }
        } else if ($perfil->agenda_publica == 0) {
            redirect('/');
        }

        $usuario->sobrenome = $perfil->sobrenome;

        $eventos = $this->usuarios_agenda_m->get_all_by_usuario($usuario->id);

        foreach ($eventos as $evento) {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        }

        $dados = array(
                'usuario' => $usuario,
                'eventos' => $eventos,
            );

        $html = $this->load->view('_partials/_agenda_pdf', $dados, TRUE);

        $pdf = new TCPDF();

        $pdf->SetTitle('Agenda Cultural de '. $usuario->nome. ' '. $usuario->sobrenome);
        $pdf->SetSubject('TCPDF Tutorial');

        $pdf->setPrintHeader(FALSE);
        $pdf->setPrintFooter(FALSE);

        $pdf->AddPage();

        $pdf->writeHTML($html, TRUE, FALSE, FALSE, FALSE, 'C');

        $pdf->Output('agenda_cultural_'. $usuario->nome .'.pdf', 'I');


        $this->load->view('agenda_pdf', $dados);
    }

    public function usuario_perfil($id_or_username)
    {
        $validation_rules = array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required',
            ),

            array(
                'field' => 'sobrenome',
                'label' => 'Sobrenome',
                'rules' => 'trim|required',
            ),

            array(
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'trim|required|valid_email|callback__checar_email',
            ),

            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'callback__valida_senha',
            ),

            array(
                'field' => 'agenda_publica',
                'label' => 'Agenda pública?',
                'rules' => 'trim|required',
            ),

        );

        $success = FALSE;

        $usuario = $this->get_by_username(urldecode($id_or_username));

        if (! $usuario && is_numeric($id_or_username)) {
            $usuario = $this->usuarios_m->get($id_or_username);
        }

        if(! $usuario)
            show_404();

        $perfil = $this->perfis_site_m->get_by_usuario($usuario->id);

        if (! $perfil)
            show_404();

        $usuario->username = $perfil->username;
        $usuario->sobrenome = $perfil->sobrenome;
        $usuario->agenda_publica = $perfil->agenda_publica;
        $usuario->avatar = NULL;

        if ($this->usuario->id !== $usuario->id) {
            redirect('usuario/'.urlencode($usuario->username).'/agenda');
        }

        if ($this->input->post()) {
            if ($avatar_file_id = $this->input->post('avatar_file_id')) {
                $usuario->avatar = $this->arquivos->obter($avatar_file_id);
            }
            else {
                $usuario->avatar = NULL;
            }
        }
        elseif ($perfil->avatar_file_id) {
            $usuario->avatar = $this->arquivos->obter($perfil->avatar_file_id);
        }
        else {
            $usuario->avatar = NULL;
        }

        if ($this->input->post()) {

            if (is_uploaded_file(@$_FILES['avatar']['tmp_name'])) {
                $temp = $this->arquivos->adicionar(array(
                    'nome' => $_FILES['avatar']['name'],
                    'caminho' => $_FILES['avatar']['tmp_name'],
                    'tipo_mime' => $_FILES['avatar']['type'],
                ), TRUE);

                if ($temp !== FALSE) {
                    $_POST['avatar_file_id'] = $temp[0];
                    $usuario->avatar = $this->arquivos->obter($temp[0]);
                }
            }

            $this->form_validation->set_rules($validation_rules);

            if ($this->form_validation->run()) {
                $senha = $this->input->post('senha');

                $data = array(
                        'ativo' => '1',
                        'nome' => $this->input->post('nome'),
                        'email' => $this->input->post('email'),
                        'nivel' => '0',
                    );

                if (! empty($senha)) {
                    $data['senha'] = sha1($senha);
                }

                if ($this->usuarios_m->update($usuario->id, $data)) {

                    $data = array(
                            'sobrenome' => $this->input->post('sobrenome'),
                            'agenda_publica' => $this->input->post('agenda_publica'),
                        );

                    if ($this->input->post('avatar_file_id')) {
                        $data['avatar_file_id'] = $this->input->post('avatar_file_id');
                    }

                    $this->perfis_site_m->update_by_usuario($usuario->id, $data);

                    $this->session->set_flashdata('success', 'Usuário atualizado com sucesso.');
                    $success = TRUE;
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao atualizar usuário.');
                }

            }
        }

        $dados = array(
                'usuario' => $usuario,
                'success' => $success,
                'title' => 'Varadouro Cultural - Perfil: '.$usuario->nome,
                'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
            );

        $this->layout->view('usuario_perfil', $dados);
    }

    public function projetos()
    {
        $projeto = $this->projetos_m->get_first();

        $imagens = $this->projetos_imagens_m->get_all();

        $dados = array(
                'projeto' => $projeto,
                'imagens' => $imagens,
                'title' => 'Varadouro Cultural - Projetos',
                'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
            );

        $this->layout->view('projetos', $dados);
    }

    public function quem_somos()
    {
        $sobre = $this->sobre_m->get_first();

        $dados = array(
            'sobre' => $sobre,
            'layout_namespace' => 'location',
            'title' => 'Conheça mais sobre o Varadouro Cultural',
            'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
        );

        $this->layout->view('quem_somos', $dados);
    }

    public function registrar()
    {
        $validation_rules = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'trim|required',
            ),

            array(
                'field' => 'sobrenome',
                'label' => 'Sobrenome',
                'rules' => 'trim|required',
            ),

            array(
                'field' => 'username',
                'label' => 'Nome de usuário',
                'rules' => 'trim|callback__checar_username',
            ),

            array(
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'trim|required|valid_email|callback__checar_email',
            ),

            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'callback__valida_senha',
            ),
        );

        if($this->get_user_info())
            redirect('/');

        $usuario = new stdClass();

        $usuario->nome = $this->input->post('name');
        $usuario->sobrenome = $this->input->post('sobrenome');
        $usuario->username = $this->input->post('username');
        $usuario->email = $this->input->post('email');
        $usuario->avatar = NULL;

        if($this->input->post()) {
            if ($this->input->post("avatar_file_id")) {
                $usuario->avatar = $this->arquivos->obter($this->input->post("avatar_file_id"));
            }

            if (is_uploaded_file(@$_FILES['avatar']['tmp_name'])) {
                $temp = $this->arquivos->adicionar(array(
                    'nome' => $_FILES['avatar']['name'],
                    'caminho' => $_FILES['avatar']['tmp_name'],
                    'tipo_mime' => $_FILES['avatar']['type'],
                ), TRUE);

                if ($temp !== FALSE) {
                    $_POST['avatar_file_id'] = $temp[0];
                    $usuario->avatar = $this->arquivos->obter($temp[0]);
                }
            }

            $this->form_validation->set_rules($validation_rules);

            if ($this->form_validation->run()) {

                $data = array(
                        'ativo' => '1',
                        'nome' => $this->input->post('name'),
                        'email' => $this->input->post('email'),
                        'senha' => sha1($this->input->post('senha')),
                        'nivel' => '0',
                    );

                if ($id = $this->usuarios_m->insert($data)) {
                    $data = array(
                            'usuario_id' => $id,
                            'username' => $this->input->post('username'),
                            'sobrenome' => $this->input->post('sobrenome'),
                        );

                    if ($this->input->post('avatar_file_id')) {
                        $data['avatar_file_id'] = $this->input->post('avatar_file_id');
                    }

                    if ($this->input->post('avatar_file_id')) {
                        $this->arquivos_m->update($this->input->post('avatar_file_id'), array(
                                    'temp' => FALSE,
                                ));
                    }

                    $this->perfis_site_m->insert($data);
                    $this->session->set_flashdata('success', 'Usuário adicionado com sucesso.');
                    redirect('login');
                }
                else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar usuário.');
                }
            }

            foreach ($validation_rules as $rule) {
                $usuario->{$rule['field']} = $this->input->post($rule['field']);
            }
        }

        $dados = array(
                'usuario' => $usuario,
                'title' => 'Varadouro Cultural - Registre-se',
                'meta_description' => 'Site do Varadouro Cultural, rede formada por agentes e grupos culturais atuantes no Centro Histórico de João Pessoa, Paraíba',
            );

        $this->layout->view('registrar', $dados);
    }

    public function recuperar()
    {
        if($this->get_user_info())
            redirect('/');

        $feedback = '';
        $error = FALSE;

        $email = $this->input->post('email');

        $u = $this->perfis_site_m->get_by_email($email);

        if ($u) {
            $this->db->trans_start();

            $this->usuarios_m->update($u->id, array(
                'senha' => sha1($this->gerar_senha()),
            ));

            $this->db->trans_complete();

            $this->load->library('email');

            $this->email->from('varadourocultural@gmail.com', 'Admin');
            $this->email->to($u->email);

            $this->email->subject('Varadouro Cultural: Recuperação de senha');

            $msg = <<<EOS
Uma nova senha foi gerada para você. Esta é sua senha:

{$this->senha_gerada}
EOS;

            $this->email->message($msg);
            $this->email->send();


            $feedback = 'Mensagem enviada com sucesso.';
        }
        else {
            $feedback = 'E-mail não encontrado.';
            $error = TRUE;
        }

        $dados = array(
                'feedback' => $feedback,
                'error' => $error,
            );

        $this->layout->view('login', $dados);
    }

    public function logout()
    {
        $this->session->unset_userdata('usuario_site');
        $this->session->unset_userdata('usuario');
        $this->usuario = NULL;

        $this->load->vars(array(
            'usuario' => NULL,
        ));

        redirect('/');
    }

    public function muda_agenda($evento_id)
    {
        $user = $this->get_user_info();

        if(! $user)
            redirect('/');

        if(! $this->usuarios_agenda_m->search($user->id, $evento_id)) {
            $this->usuarios_agenda_m->insert($user->id, $evento_id);
        }
        else {
            $this->usuarios_agenda_m->delete($user->id, $evento_id);
        }

        redirect('usuario/'.$user->username.'/agenda');
    }

    private function gerar_senha()
    {
        $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
        srand((double) microtime() * 1000000);

        $i = 0;
        $senha = '';

        while ($i <= 9) {
            $num = rand() % (strlen($chars) - 1);
            $tmp = substr($chars, $num, 1);
            $senha .= $tmp;
            $i++;
        }

        return $this->senha_gerada = $senha;
    }

    private function login_usuario()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('senha');

        $ui = $this->perfis_site_m->validate($username, $password);

        if (! $ui) {
            return FALSE;
        }
        else {
            $u = new stdClass();

            $u->id = $ui->id;
            $u->nome = $ui->nome;
            $u->sobrenome = $ui->sobrenome;
            $u->username = $ui->username;

            $this->session->set_userdata('usuario_site', $u);
            $this->usuario = $u;

            $this->load->vars(array(
                'usuario' => $this->usuario,
            ));

            return TRUE;
        }
    }

    private function cadastrar_usuario()
    {
        $validation_rules = array(
            array(
                'field' => 'nome',
                'label' => 'Nome completo',
                'rules' => 'trim|required',
            ),

            array(
                'field' => 'username',
                'label' => 'Nome de usuário',
                'rules' => 'trim|required',
            ),

            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email|callback__checar_email',
            ),

            array(
                'field' => 'confirmacao_email',
                'label' => 'Confirmação de Email',
                'rules' => 'trim|required|valid_email|callback__checar_confirmacao',
            ),
        );

        $this->form_validation->set_rules($validation_rules);

        if ($this->form_validation->run()) {
            $this->db->trans_start();

            $usuario_id = $this->usuarios_m->insert(array(
                'admin' => 0,
                'ativo' => 1,
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'senha' => sha1($this->gerar_senha()),
            ));

            $this->perfis_site_m->insert(array(
                'usuario_id' => $usuario_id,
                'nome' => $this->input->post('nome'),
                'sobrenome' => $this->input->post('sobrenome'),
            ));

            $this->db->trans_complete();

            return $usuario_id;
        }

        return FALSE;
    }

    public function get_by_username($username)
    {
        $usuario = $this->perfis_site_m->get_by_username($username);

        if($usuario) {
            return $this->usuarios_m->get($usuario->usuario_id);
        }
        else {
            return NULL;
        }
    }

    private function get_user_info()
    {
        if($this->usuario) {
            return $this->usuario;
        }
        else {
            return NULL;
        }
    }

    public function busca_ajax()
    {
        header('Content-Type: application/json');

        $q = (string) $this->input->get_post('q');

        $temp = $this->input->get_post('t');

        if($temp) {
            $t = (array) $this->input->get_post('t');
        }
        else{
            $t = array('evento', 'noticia', 'espaco_cultural', 'agente_cultural');
        }

        $start = intval($this->input->get_post('start'));

        $results_temp = $this->busca_m->buscar($q, $t, 6, $start);
        $hits = $results_temp['hits'];
        $itens = array();

        foreach ($hits['hits'] as $temp) {
            $data = $temp['_source'];
            $item = new stdClass();

            if ($temp['_type'] == 'evento') {
                $item->nome = $data['titulo'];
                $item->tipo = $temp['_type'];
                $item->href = '/evento/'.$data['slug'];
                $item->data = $data['data'];
            }
            else if ($temp['_type'] == 'noticia') {
                $item->nome = $data['titulo'];
                $item->tipo = $temp['_type'];
                $item->href = '/noticias/'.$data['slug'];
                $item->data = $data['data'];
            }
            else if ($temp['_type'] == 'espaco_cultural') {
                $item->nome = $data['nome_espaco'];
                $item->tipo = $temp['_type'];
                $item->href = '/espacos/'.$data['slug'];
            }
            else if ($temp['_type'] == 'agente_cultural') {
                $item->nome = $data['nome_responsavel'];
                $item->tipo = $temp['_type'];
                $item->href = '/agentes/'.$data['slug'];
            }

            $itens[] = $item;
        }

        $partial = $this->load->view('_partials/_resultado_busca', array('itens' => $itens), TRUE);

        echo json_encode(array(
            'status' => 'ok',
            'erros' => NULL,
            'start' => $start + (count($itens) - 2 + 1),
            'partial' => $partial,
            'has_more' => (count($itens) > 5),
        ));
    }


    public function noticias_ajax()
    {
        header('Content-Type: application/json');

        $start = intval($this->input->get_post('start'));

        $noticias = $this->noticias_m->get_all_with_img(10, $start);

        $partial = $this->load->view('_partials/_noticias', array('noticias' => $noticias), TRUE);

        echo json_encode(array(
            'status' => 'ok',
            'erros' => NULL,
            'partial' => $partial,
            'start' => $start + (count($noticias) - 2 + 1),
            'has_more' => (count($noticias) > 9),
        ));
    }


    public function espacos_agentes_ajax()
    {
        header('Content-Type: application/json');

        $start_espacos = intval($this->input->get_post('start_espacos'));
        $start_agentes = intval($this->input->get_post('start_agentes'));

        $load_espacos = intval($this->input->get_post('load_espacos'));
        $load_agentes = intval($this->input->get_post('load_agentes'));

        $espacos = array();
        $agentes = array();

        if ($load_espacos == 1) {
            $espacos = $this->espacos_culturais_m->get_all_with_img(5, $start_espacos, array(), TRUE);

            foreach ($espacos as $espaco)
            {
                $espaco->filtros = array();

                $slug = 'area-de-atuacao-cultural';

                $espaco->area_primaria = $this->atributos_m->get($espaco->area_atuacao_primaria);
                $espaco->filtros = $this->espacos_culturais_atributos_m->get_first_children($espaco->id, $slug, 4);
                array_push($espaco->filtros, $this->atributos_m->get($espaco->tipo_espaco));

                $espaco->filtros_str = '';

                $num_filtros = count($espaco->filtros);

                foreach ($espaco->filtros as $filtro)
                {
                    if($filtro == $espaco->filtros[$num_filtros - 1])
                    {
                        $espaco->filtros_str .= $filtro->nome;
                    } else
                    {
                        $espaco->filtros_str .= $filtro->nome.', ';
                    }
                }
            }
        }

        if ($load_agentes == 1) {
            $agentes = $this->agentes_culturais_m->get_all_with_img(3, $start_agentes, array(), TRUE);

            foreach ($agentes as $agente)
            {
                $agente->filtros = array();

                $slug = 'area-de-atuacao-cultural';

                $agente->area_primaria = $this->atributos_m->get($agente->area_atuacao_primaria);
                $agente->filtros = $this->agentes_culturais_atributos_m->get_first_children($agente->id, $slug, 5);

                $agente->filtros_str = '';

                $num_filtros = count($agente->filtros);

                foreach ($agente->filtros as $filtro)
                {
                    if($filtro == $agente->filtros[$num_filtros - 1])
                    {
                        $agente->filtros_str .= $filtro->nome;
                    } else
                    {
                        $agente->filtros_str .= $filtro->nome.', ';
                    }
                }
            }
        }

        $has_more_agentes = (count($agentes) > 2);
        $has_more_espacos = (count($espacos) > 4);

        $partial_espacos = $this->load->view('_partials/_espacos', array('espacos' => $espacos), TRUE);
        $partial_agentes = $this->load->view('_partials/_agentes', array('agentes' => $agentes), TRUE);

        echo json_encode(array(
            'status' => 'ok',
            'erros' => NULL,
            'partial_espacos' => $partial_espacos,
            'partial_agentes' => $partial_agentes,
            'espacos' => $espacos,
            'agentes' => $agentes,
            'has_more' => ($has_more_agentes || $has_more_espacos),
            'has_more_agentes'=> $has_more_agentes,
            'has_more_espacos' => $has_more_espacos,
            'load_espacos' => $has_more_espacos,
            'load_agentes' => $has_more_agentes,
            'start_agentes' => $start_agentes + (count($agentes) - 2 + 1),
            'start_espacos' => $start_espacos + (count($espacos) - 2 + 1),
        ));
    }

    public function agenda_ajax()
    {
        header('Content-Type: application/json');

        $start = intval($this->input->get_post('start'));

        $eventos = $this->eventos_m->get_all(10, $start, TRUE);

        foreach ($eventos as $evento)
        {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        }

        $partial = $this->load->view('_partials/_eventos', array('eventos' => $eventos), TRUE);

        echo json_encode(array(
            'status' => 'ok',
            'erros' => NULL,
            'eventos' => $eventos,
            'partial' => $partial,
            'start' => $start + (count($eventos) - 2 + 1),
            'has_more' => (count($eventos) > 9),
        ));
    }

    public function usuario_agenda_ajax($username)
    {
        header('Content-Type: application/json');

        $usuario = $this->get_by_username(urldecode($username));

        if(! $usuario)
            show_404();

        $start = intval($this->input->get_post('start'));

        $u = $this->session->userdata('usuario_site');

        $eventos = $this->usuarios_agenda_m->get_all_by_usuario($usuario->id, 10, $start);

        foreach ($eventos as $evento)
        {
            $evento->espaco = $this->espacos_culturais_m->get($evento->espaco_cultural, TRUE);
        }

        $partial = $this->load->view('_partials/_eventos_agenda', array('eventos' => $eventos, 'u' => $u), TRUE);

        echo json_encode(array(
            'status' => 'ok',
            'erros' => NULL,
            'usuario' => $usuario,
            'eventos' => $eventos,
            'partial' => $partial,
            'start' => $start + (count($eventos) - 2 + 1),
            'has_more' => (count($eventos) > 9),
        ));
    }

    public function map_home_ajax() {
        header('Content-Type: application/json');

        $this->load->model('espacos_culturais_fotos_m');
        $this->load->model('agentes_culturais_fotos_m');
        $this->load->model('espacos_culturais_horarios_m');
        $this->load->model('agentes_culturais_horarios_m');

        $espacos = array();
        $agentes = array();

        $areas = $this->input->get_post('a');
        $tipos = $this->input->get_post('t');

        if($areas || $tipos) {
            if($areas){
                $areas = (array) $this->input->get_post('a');

                foreach($areas as $area) {
                    $espacos_temp = $this->espacos_culturais_m->get_all_by_area($area, TRUE);
                    $agentes_temp = $this->agentes_culturais_m->get_all_by_area($area, TRUE);

                    if($espacos_temp) {
                       $espacos = array_merge($espacos, (array) $espacos_temp);
                    }

                    if($agentes_temp) {
                       $agentes = array_merge($agentes, (array) $agentes_temp);
                    }
                }
            }

            if($tipos) {
                $tipos = (array) $this->input->get_post('t');

                foreach($tipos as $tipo) {
                    $espacos_temp = $this->espacos_culturais_m->get_all_by_tipo($tipo, TRUE);

                    if($espacos_temp) {
                       $espacos = array_merge($espacos, (array) $espacos_temp);
                    }
                }
            }

        } else {
            $espacos = $this->espacos_culturais_m->get_all(NULL, NULL, TRUE);
            $agentes = $this->agentes_culturais_m->get_all(NULL, NULL, TRUE);
        }

        foreach ($espacos as $espaco) {
            $espaco->area_primaria = $this->atributos_m->get($espaco->area_atuacao_primaria);
            $espaco->sigla = $espaco->area_primaria->sigla;
            $espaco->area_primaria = $espaco->area_primaria->nome;

            $espaco->natureza_juridica = $this->naturezas_juridicas[$espaco->natureza_juridica];

            $espaco->horario = $this->espacos_culturais_horarios_m->get_all_by_espaco_cultural($espaco->id);

            $horario_str = '';
            $horarios = $espaco->horario;
            $num_dias = count($espaco->horario);

            foreach ($horarios as $horario)
            {
                if ($horario->horario_abertura && $horario->horario_fechamento)
                {
                    $horario_str .= utf8_encode(substr(strtoupper(utf8_decode($this->dias_semana[$horario->dia_semana])), 0, 3)).' - ';

                    $horario_abertura = explode(':', $horario->horario_abertura);
                    $horario_fechamento = explode(':', $horario->horario_fechamento);

                    if ($horario == $espaco->horario[$num_dias - 1])
                    {
                        $horario_str .= $horario_abertura[0] .'H' . $horario_abertura[1]
                        . '-' . $horario_fechamento[0] . 'H' . $horario_fechamento[1];
                    } else
                    {
                        $horario_str .= $horario_abertura[0] .'H' . $horario_abertura[1]
                        . '-' . $horario_fechamento[0] . 'H' . $horario_fechamento[1] . '; ';
                    }
                }
            }

            $espaco->horario = $horario_str;

            if ($espaco->fechado_almoco == 0) {
                $espaco->fechado_almoco = 'Não';
            } else {
                $espaco->fechado_almoco = 'Sim';
            }

            $fotos = $this->espacos_culturais_fotos_m->get_all_by_espaco_cultural($espaco->id);

            if($fotos) {
                $espaco->foto = $fotos[0]->foto_file_id;
            } else {
                $espaco->foto = 0;
            }
        }

        foreach ($agentes as $agente) {
            $agente->area_primaria = $this->atributos_m->get($agente->area_atuacao_primaria);
            $agente->sigla = $agente->area_primaria->sigla;
            $agente->area_primaria = $agente->area_primaria->nome;

            $agente->horario = $this->agentes_culturais_horarios_m->get_all_by_agente_cultural($agente->id);

            $horario_str = '';
            $horarios = $agente->horario;
            $num_dias = count($agente->horario);

            foreach ($horarios as $horario)
            {
                if ($horario->horario_abertura && $horario->horario_fechamento)
                {
                    $horario_str .= utf8_encode(substr(strtoupper(utf8_decode($this->dias_semana[$horario->dia_semana])), 0, 3)).' - ';

                    $horario_abertura = explode(':', $horario->horario_abertura);
                    $horario_fechamento = explode(':', $horario->horario_fechamento);

                    if ($horario == $agente->horario[$num_dias - 1])
                    {
                        $horario_str .= $horario_abertura[0] .'H' . $horario_abertura[1]
                        . '-' . $horario_fechamento[0] . 'H' . $horario_fechamento[1];
                    } else
                    {
                        $horario_str .= $horario_abertura[0] .'H' . $horario_abertura[1]
                        . '-' . $horario_fechamento[0] . 'H' . $horario_fechamento[1] . '; ';
                    }
                }
            }

            $agente->horario = $horario_str;

            if ($agente->fechado_almoco == 0) {
                $agente->fechado_almoco = 'Não';
            } else {
                $agente->fechado_almoco = 'Sim';
            }

            $agente->natureza_juridica = $this->naturezas_juridicas[$agente->natureza_juridica];

            $fotos = $this->agentes_culturais_fotos_m->get_all_by_agente_cultural($agente->id);

            if($fotos) {
                $agente->foto = $fotos[0]->foto_file_id;
            } else {
                $agente->foto = 0;
            }
        }

        $this->load->view('_partials/_home_map', array('espacos' => $espacos, 'agentes' => $agentes), TRUE);

        echo json_encode(array(
            'status' => 'ok',
            'erros' => NULL,
            'areas' => $areas,
            'tipos' => $tipos,
            'espacos' => $espacos,
            'agentes' => $agentes,
        ));
    }

    public function _checar_email($email)
    {
        if($this->usuario) {
            $id_usuario = $this->usuario->id;
        }
        else {
            $id_usuario = NULL;
        }

        if($id_usuario) {
            $r = $this->usuarios_m->checar_email($email, $id_usuario);
        }
        else {
            $r = $this->usuarios_m->checar_email($email);
        }

        if ($r) {
            $this->form_validation->set_message('_checar_email', 'E-mail já cadastrado.');

            return FALSE;
        }

        return TRUE;
    }

    public function _checar_username($username)
    {
        if (strlen($username) >= 4 && strlen($username) <= 20) {
            if (preg_match('/^[._a-zA-Z0-9]+$/', $username)) {
                $r = $this->perfis_site_m->checar_username($username);

                if ($r) {
                    $this->form_validation->set_message('_checar_username', 'Esse usuário já existe.');

                    return FALSE;
                }

                return TRUE;
            } else {
                $this->form_validation->set_message('_checar_username', 'Apenas são permitidos números, letras, "." e "_" no nome do usuário.');

                return FALSE;
            }

        } else {
            $this->form_validation->set_message('_checar_username', 'O nome do usuário tem que possuir entre 4 e 20 caracteres.');

            return FALSE;
        }

        return TRUE;
    }

    public function _valida_senha($senha)
    {
        $method = $this->router->fetch_method();

        if ($method == 'registrar') {
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
            elseif ($senha != $this->input->post('confirmacao_senha')) {
                $confirma = $this->input->post('confirmacao_senha');

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
        elseif ($method == 'usuario_perfil') {
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
                elseif ($senha != $this->input->post('confirmacao_senha')) {
                    $confirma = $this->input->post('confirmacao_senha');

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
                else {
                    return TRUE;
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
