<?php

function check_session()
{
    // verificando se há um usuário ativo na sessão
    return isset($_SESSION['user']);
}

//============================================================================================================
function printData($data, $die = true)
{
    echo '<pre>';
    if (is_object($data) || is_array($data)) {
        print_r($data);
    } else {
        echo $data;
    }

    if ($die) {
        die('<br> FIM </br>');
    }
}

//============================================================================================================
function timing($time)
{
    $time = time() - $time;
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'ano',
        2592000 => 'mês',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'minuto',
        1 => 'segundo'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return "agora mesmo";
    }
    return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
}
