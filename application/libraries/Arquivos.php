<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('FILES_PATH', __DIR__ . '/../../uploads');
define('IDADE_MAXIMA_TEMPORARIOS', 3600); // em segundos

class Arquivos {

    private $CI = NULL;

    private $path = NULL;

    public function __construct($files_path = FILES_PATH)
    {
        $this->CI =& get_instance();
        $this->path = $files_path;
        $this->CI->load->model('arquivos_m');
        $this->CI->load->helper('url');

        $this->CI->load->library('slug');
    }

    public function adicionar($dados, $temporario = FALSE)
    {
        if (empty($dados['nome']))
            $dados['nome'] = pathinfo($dados['caminho'], PATHINFO_BASENAME);

        if (empty($dados['tipo_mime'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $ct = finfo_file($finfo, $dados['caminho']);

            if ($ct)
                $dados['tipo_mime'] = $ct;
        }

        if (empty($dados['tipo_mime']))
            return FALSE;

        list($tipo_geral, $_) = explode('/', $dados['tipo_mime'], 2);

        if ($tipo_geral == 'image') {
            $ii = getimagesize($dados['caminho']);
            $largura_imagem = $ii[0];
            $altura_imagem = $ii[1];
        }
        else {
            $largura_imagem = 0;
            $altura_imagem = 0;
        }

        $ext = pathinfo($dados['nome'], PATHINFO_EXTENSION);
        $fn = uniqid() . '-' . $dados['nome'];
        $r = @copy($dados['caminho'], $this->path . '/' . $fn);

        if (! $r)
            return FALSE;

        if (empty($dados['slug'])) {
            $this->CI->slug->set_config(array(
                'field' => 'slug',
                'table' => 'arquivos',
                'id' => 'id',
            ));

            $tempfn = pathinfo($dados['nome'], PATHINFO_FILENAME);
            $dados['slug'] = $this->CI->slug->create_uri($tempfn);
        }

        $id = $this->CI->arquivos_m->insert(array(
            'slug' => $dados['slug'],
            'temp' => $temporario,
            'nome_original' => $dados['nome'],
            'nome_arquivo' => $fn,
            'extensao' => $ext,
            'tipo_geral' => $tipo_geral,
            'tipo_mime' => $dados['tipo_mime'],
            'tamanho' => filesize($dados['caminho']),
            'largura_imagem' => $largura_imagem,
            'altura_imagem' => $altura_imagem,
            'data_criacao' => date('Y-m-d H:i:s'),
            'data_modificacao' => date('Y-m-d H:i:s'),
        ));

        return array(
            $id, $this->path . '/' . $dados['nome']
        );
    }

    public function obter($id)
    {
        $f = $this->CI->arquivos_m->get($id);

        if ($f)
            $f->caminho = $this->path . '/' . $f->nome_arquivo;

        return $f;
    }

    public function excluir($id, $manter_arquivo = FALSE)
    {
        $f = $this->obter($id);

        if (! $f)
            return FALSE;

        if (! $manter_arquivo) {
            $r = @unlink($f->caminho);

            if ($r !== TRUE)
                throw new Exception('Erro ao excluir arquivo');
        }

        $this->CI->arquivos_m->delete($id);
    }

    public function excluir_temporarios($idade_maxima = IDADE_MAXIMA_TEMPORARIOS)
    {
        $temporarios = $this->CI->arquivos_m->get_all_idade($idade_maxima, TRUE);

        foreach ($temporarios as $t)
            $this->excluir($t->id);

        return TRUE;
    }

}
