<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function gerar_seletor_atributos($nome, $atributo, $selecionados = array(), $nivel = 1, $tipo = NULL)
{
    $CI =& get_instance();

    $CI->load->model('atributos_m');
    $CI->load->helper('form');

    $out = '';

    if (($atributo->tipo == 'selecao-simples') || (! empty($tipo) && ($tipo == 'selecao-simples'))) {
        $selecionado = NULL;

        $valores = array(
            '' => '-- Selecione --',
        );

        foreach ($atributo->itens as $item) {
            $valores[$item->id] = $item->nome;

            if (is_array($selecionados) && in_array($item->id, $selecionados)) {
                $selecionado = $item->id;
            }
        }

        if ($selecionado) {
            $item = $CI->atributos_m->get($selecionado, TRUE);

            if ($item->itens) {
                $nome_original = $nome;
                $nome .= "_{$nivel}";
                $out .= gerar_seletor_atributos($nome_original, $item, $selecionados, $nivel + 1);
            }
        }

        $out = form_dropdown($nome, $valores, $selecionado) . $out;
    }
    else if ($atributo->tipo == 'selecao-multipla') {
        $out = '<ul>';

        foreach ($atributo->itens as $item) {
            $out .= '<li>';
            $out .= '<label class="checkbox">';
            $checked = (is_array($selecionados) && in_array($item->id, $selecionados))
                    || ($selecionados === TRUE);
            $out .= form_checkbox("{$nome}[]", $item->id, $checked);
            $out .= htmlspecialchars($item->nome);
            $out .= '</label>';
            $item = $CI->atributos_m->get($item->id, TRUE);

            if ($item->itens) {
                $out .= gerar_seletor_atributos($nome, $item, $checked ? TRUE : $selecionados);
            }

            $out .= '</li>';
        }

        $out .= '</ul>';
    }

    return $out;
}
