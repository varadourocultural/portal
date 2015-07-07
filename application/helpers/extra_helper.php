<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('strip_tags_better'))
{
    function strip_tags_better($str, $allowable_tags = NULL)
    {
        // acrescenta espaço entre tags
        $str = preg_replace('/[>]([^\s])/', '> \1', $str);

        return strip_tags($str, $allowable_tags);
    }
}
