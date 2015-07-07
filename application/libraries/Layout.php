<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout {

    private $CI = NULL;

    private $layout;

    public function __construct($layout = 'layout_default')
    {
        $this->CI =& get_instance();
        $this->layout = $layout;
    }

    public function set_layout($layout)
    {
      $this->layout = $layout;
    }

    public function view($view, $data = NULL, $return = FALSE)
    {
        if ($this->layout) {
            $loaded_data = array();
            $loaded_data['content_for_layout'] = $this->CI->load->view($view, $data, TRUE);

            if ($return) {
                return $this->CI->load->view($this->layout, $loaded_data, TRUE);
            }
            else {
                $this->CI->load->view($this->layout, $loaded_data, FALSE);
            }
        }
        else {
            if ($return) {
                return $this->CI->load->view($view, $data, TRUE);
            }
            else {
                $this->CI->load->view($view, $data, FALSE);
            }
        }
    }

}
