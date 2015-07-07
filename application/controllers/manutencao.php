<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manutencao extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('arquivos_m');
        $this->load->library('arquivos');
    }

    public function index()
    {
        if (! $this->input->is_cli_request())
            show_error('Acesso não autorizado', 403);

        $arquivos = $this->arquivos_m->get_all_idade(IDADE_MAXIMA_TEMPORARIOS, TRUE, 100);

        while (! empty($arquivos)) {
            foreach ($arquivos as $arq)
                $this->arquivos->excluir($arq->id);

            $arquivos = $this->arquivos_m->get_all_idade(IDADE_MAXIMA_TEMPORARIOS, TRUE, 100);
        }
    }

}
