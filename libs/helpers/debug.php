<?php

/**
 * Dump and die: muestra una representación de una o varias variables y
 * detiene la ejecución. Acepta múltiples argumentos, por ejemplo:
 *
 * Example:
 * dd('hola', $_GET);
 * dd($user);
 *
 * @param mixed ...$valores Uno o varios valores a mostrar (variadic)
 * @return void
 */
function dd(mixed ...$valores): void
{
    echo '<pre>';
    foreach ($valores as $v) {
        var_dump($v);
    }
    echo '</pre>';
    exit;
}
