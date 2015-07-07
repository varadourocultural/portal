<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('date_create_from_format')) {
    function date_create_from_format($format, $value)
    {
        $format = str_replace(array('Y','m','d', 'H', 'i','a'),
                array('%Y','%m','%d', '%I', '%M', '%p' ), $format);

        $ugly = strptime($value, $format);

        $ymd = sprintf('%04d-%02d-%02d %02d:%02d:%02d',
                $ugly['tm_year'] + 1900, $ugly['tm_mon'] + 1, $ugly['tm_mday'],
                $ugly['tm_hour'], $ugly['tm_min'], $ugly['tm_sec']);

        $d = new DateTime($ymd);

        return $d;
    }
}

class MY_Form_validation extends CI_Form_validation {

	public function __construct()
    {
		parent::__construct();

		$this->CI =& get_instance();
	}

    public function error_array()
    {
        if (count($this->_error_array) === 0) {
            return FALSE;
        }
        else {
            return $this->_error_array;
        }
    }

    public function clear_rules()
    {
        $this->_field_data = array();
    }

    public function db_date($str, $fmt)
    {
        if (! $str)
            return NULL;

        $res = @date_create_from_format($fmt, $str);

        if ($res === FALSE) {
            $this->CI->form_validation->set_message('db_date', 'O campo %s não é uma data válida.');

            return FALSE;
        }
        else {
            return $res->format('Y-m-d');
        }
    }

    public function db_time($str, $fmt)
    {
        if (! $str)
            return NULL;

        $res = @date_create_from_format($fmt, $str);

        if ($res === FALSE) {
            $this->CI->form_validation->set_message('db_time', 'O campo %s não é uma data válida.');

            return FALSE;
        }
        else {
            return $res->format('H:i:s');
        }
    }

    public function db_datetime($str, $fmt)
    {
        if (! $str)
            return NULL;

        $res = @date_create_from_format($fmt, $str);

        if ($res === FALSE) {
            $this->CI->form_validation->set_message('db_datetime', 'O campo %s não é uma data válida.');

            return FALSE;
        }
        else {
            return $res->format('Y-m-d H:i:s');
        }
    }

}
