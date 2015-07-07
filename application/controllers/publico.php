<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('USE_XSENDFILE_IF_AVAIABLE', TRUE);

class Publico extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('arquivos');
        $this->load->library('image_lib');
        $this->load->model('usuarios_m');
        $this->load->model('arquivos_m');
        $this->load->model('perfis_site_m');

    }

    public function login()
    {

        $temp = $this->session->userdata('usuario_site');

        if ($temp) {
            $ui = $this->perfis_site_m->get_by_usuario($temp->id);
            $this->session->set_userdata('usuario', $ui);

            redirect('/admin');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $ui = $this->usuarios_m->validate($username, $password);

            if (! $ui) {
                $this->session->set_flashdata('error', 'Email ou senha inválida.');

                redirect('/admin/login');
            }
            else {
                $this->session->set_userdata('usuario', $ui);

                redirect('/admin');
            }
        }

        $this->layout->set_layout('admin/layout_simples');
        $this->layout->view('admin/admin/login');
    }

    public function logout()
    {
        $this->session->unset_userdata('usuario');
        redirect('admin/login');
    }

    public function atributos_ajax($atributo_ascendente_id)
    {
        $this->load->model('atributos_m');

        header('Content-Type: application/json');

        echo json_encode($this->atributos_m->get_all_filhos($atributo_ascendente_id));
    }

    public function download($id_or_slug)
    {
        $arquivo = $this->get_file($id_or_slug);
        $this->send_file($arquivo, TRUE);
    }

    public function file($id_or_slug)
    {
        $arquivo = $this->get_file($id_or_slug);
        $this->send_file($arquivo, FALSE);
    }

    public function image($id_or_slug)
    {
        $arquivo = $this->get_file($id_or_slug);

        if ($arquivo->tipo_geral != 'image') {
            header('HTTP/1.1 400 Bad Request');

            die;
        }

        $this->send_file($arquivo);
    }

    public function thumb($id_or_slug, $largura = NULL, $altura = NULL)
    {
        $arquivo = $this->get_file($id_or_slug);
        $source_aspect_ratio = $arquivo->largura_imagem / $arquivo->altura_imagem;
        $ft = filemtime($arquivo->caminho);

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ims = $_SERVER['HTTP_IF_MODIFIED_SINCE'];

            if (strtotime($ims) >= $ft) {
                header('HTTP/1.1 304 Not Modified');

                die;
            }
        }

        if (! empty($largura) && empty($altura)) {
            $altura = $largura / $source_aspect_ratio;
        }
        else if (empty($largura) && ! empty($altura)) {
            $largura = $altura * $source_aspect_ratio;
        }
        else if (empty($largura) && empty($altura)) {
            header('HTTP/1.1 400 Bad Request');

            die;
        }

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $ft) . ' GMT');

        if ($arquivo->tipo_geral == 'image') {
            $this->resize($arquivo, $largura, $altura);
        }
        else {
            $this->build_file_img($arquivo, $largura, $altura);
        }
    }

    private function build_file_img($arquivo, $largura, $altura)
    {
        $base = APPPATH . '/../img/filetypes';
        $ext_img_path = $base . "/{$arquivo->extensao}.png";

        if (! file_exists($ext_img_path))
            $ext_img_path = $base . '/_blank.png';

        $ext_img = imagecreatefrompng($ext_img_path);
        $out = imagecreatetruecolor($largura, $altura);
        $bg_color = imagecolorallocate($out, 0xff, 0xff, 0xff);
        imagefill($out, 0, 0, $bg_color);
        $ll = imagesx($ext_img);
        $aa = imagesy($ext_img);
        $x = floor(($largura - $ll) / 2);
        $y = floor(($altura - $aa) / 2);

        imagecopyresampled($out, $ext_img, $x, $y, 0, 0, $ll,
                $aa, $ll, $aa);

        header('Content-Type: image/jpeg');

        echo imagejpeg($out, NULL, 100);
    }

    private function get_file($id_or_slug)
    {
        $arquivo = $this->arquivos->obter(intval($id_or_slug));

        if (! $arquivo) {
            header('HTTP/1.1 404 Not Found');

            die;
        }

        $ishd = FALSE;
        $fslug = $id_or_slug;
        $temp = explode('-', $id_or_slug, 2);

        if (count($temp) > 1)
            $fslug = pathinfo($temp[1], PATHINFO_FILENAME);

        if (count($temp) > 1) {
            $ext = pathinfo($temp[1], PATHINFO_EXTENSION);

            if ((strcasecmp($fslug, $arquivo->slug) != 0)
                || (strcasecmp($ext, $arquivo->extensao) != 0)) {
                header('HTTP/1.1 404 Not Found');

                die;
            }
        }

        return $arquivo;
    }

    private function resize($arquivo, $largura, $altura)
    {
        $source_aspect_ratio = $arquivo->largura_imagem / $arquivo->altura_imagem;
        $desired_aspect_ratio = $largura / $altura;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            $temp_width = $altura * $source_aspect_ratio;
            $temp_height = $altura;
        }
        else {
            $temp_width = $largura;
            $temp_height = $largura / $source_aspect_ratio;
        }

        $temp_file_md = stream_get_meta_data(tmpfile());
        $temp_file = $temp_file_md['uri'];

        $config['source_image'] = $arquivo->caminho;
        $config['new_image'] = $temp_file;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $temp_width;
        $config['height'] = $temp_height;

        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        $config['source_image'] = $temp_file;
        $config['dynamic_output'] = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $largura;
        $config['height'] = $altura;
        $config['x_axis'] = floor(($temp_width - $largura) / 2);
        $config['y_axis'] = floor(($temp_height - $altura) / 2);

        $this->image_lib->initialize($config);
        $this->image_lib->crop();
    }

    private function send_file($arquivo, $force_download = FALSE)
    {
        $modules = array();

        if (function_exists('apache_get_modules'))
            $modules = apache_get_modules();

        $mod_xsendfile_available = in_array('mod_xsendfile', $modules);

        if ($mod_xsendfile_available && USE_XSENDFILE_IF_AVAIABLE) {
            header('X-Sendfile: ' . realpath($arquivo->caminho));
            header("Content-Type: {$arquivo->tipo_mime}");

            if ($force_download)
                header("Content-Disposition: attachment; "
                        . "filename=\"{$arquivo->nome_original}\"");
        }
        else {
            error_log('Para uma melhor performance por favor instale e ative mod_xsendfile.');

            $ft = filemtime($arquivo->caminho);
            $lm = gmdate('D, d M Y H:i:s', $ft) . ' GMT';

            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                $ims = $_SERVER['HTTP_IF_MODIFIED_SINCE'];

                if (strtotime($ims) >= $ft) {
                    header('HTTP/1.1 304 Not Modified');

                    die;
                }
            }

            header('Last-Modified: ' . $lm);
            header("Content-Type: {$arquivo->tipo_mime}");
            header("Content-Length: {$arquivo->tamanho}");

            if ($force_download)
                header("Content-Disposition: attachment; "
                        . "filename=\"{$arquivo->nome_original}\"");

            readfile($arquivo->caminho);
        }
    }

    public function sigla()
    {
        $text = $this->input->get('t');
        $img = imagecreatefrompng(FCPATH . '/img/sprite/standard/map-location-large.png');

        $imageX = imagesx($img);
        $imageY = imagesy($img);

        imagealphablending($img, FALSE);
        imagesavealpha($img, TRUE);
        $white = imagecolorallocate($img, 255, 255, 255);

        $font = FCPATH . '/font/FreeMonoBold.ttf';

        $fontSize = 10;

        $textDim = imagettfbbox($fontSize, 0, $font, $text);
        $textX = ($textDim[2] - $textDim[0]) - 2;
        $textY = 11;

        $text_posX = ($imageX / 2) - ($textX / 2);
        $text_posY = ($imageY / 2) - ($textY / 2);

        imagealphablending($img, TRUE);
        imagettftext($img, $fontSize, 0, $text_posX, $text_posY, $white, $font, $text);

        header("Content-Type: image/png");
        imagepng($img);
    }

}
