<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

    function show_404($lang_id = 'br')
    {
        if (is_callable('redirect') && is_callable('site_url_lang')) {
            redirect(site_url_lang('/404', $lang_id));
        }
        else {
            parent::show_404();
        }
    }

}
