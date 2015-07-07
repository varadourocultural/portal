<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library('arquivos');
        $this->load->helper('url');
    }

    public function ck_upload()
    {
        $func_num = $this->input->get('CKEditorFuncNum');
        $ckeditor = $this->input->get('CKEditor');
        $lang_code = $this->input->get('langCode');
        $fid = NULL;
        $erros = '';

        if (is_uploaded_file(@$_FILES['upload']['tmp_name'])) {
            $temp = $this->arquivos->adicionar(array(
                'nome' => $_FILES['upload']['name'],
                'caminho' => $_FILES['upload']['tmp_name'],
                'tipo_mime' => $_FILES['upload']['type'],
            ), FALSE);

            if ($temp !== FALSE)
                $fid = $temp[0];
        }
        else {
            $erros = 'Você deve especificar um arquivo válido.';
        }

        $url = $fid ? site_url('/publico/image/' . $fid) : '';

        $out = <<<EOS
<script type="text/javascript">
    window.parent.CKEDITOR.tools.callFunction(%s, "%s", "%s");
</script>
EOS;

        printf($out, $func_num, $url, $erros);
    }

}
