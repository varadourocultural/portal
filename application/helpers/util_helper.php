<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formata_valor($val)
{
    $s = number_format(floatval($val), 2, '.', '');

    return 'R$ ' . str_replace('.', ',', $s);
}

if (! function_exists("date_create_from_format")) {
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

function ler_data($str, $format = 'short_date')
{
    $date_utils_formats = array(
        'short_date' => 'd/m/Y',
        'db_date' => 'Y-m-d',
    );

    if (isset($date_utils_formats[$format]))
        $format = $date_utils_formats[$format];

    $res = date_create_from_format($format, $str);

    return $res ? intval($res->format("U")) : NULL;
}

function formata_data($date, $format = 'short_date')
{
    $date_utils_formats = array(
        'short_date' => 'd/m/Y',
        'db_date' => 'Y-m-d',
    );

    if (isset($date_utils_formats[$format]))
        $format = $date_utils_formats[$format];

    $date = getdate($date);
    $temp = date_create();

    $temp->setDate($date['year'], $date['mon'], $date['mday']);
    $temp->setTime($date['hours'], $date['minutes'], $date['seconds']);

    return $temp->format($format);
}

function ler_data_hora($str, $format = 'short_datetime')
{
    $date_utils_formats = array(
        'short_datetime' => 'd/m/Y H:i',
        'db_date' => 'Y-m-d H:i:00',
    );

    if (isset($date_utils_formats[$format]))
        $format = $date_utils_formats[$format];

    $res = date_create_from_format($format, $str);

    return $res ? intval($res->format("U")) : NULL;
}

function formata_data_hora($datetime, $format = 'short_datetime')
{
    $date_utils_formats = array(
        'short_datetime' => 'd/m/Y H:i',
        'db_date' => 'Y-m-d H:i:00',
    );

    if (isset($date_utils_formats[$format]))
        $format = $date_utils_formats[$format];

    $date = getdate($datetime);
    $temp = date_create();

    $temp->setDate($date['year'], $date['mon'], $date['mday']);
    $temp->setTime($date['hours'], $date['minutes'], $date['seconds']);

    return $temp->format($format);
}
