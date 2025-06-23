<?php

function base_url($uri = '') {
    // Obtiene la URL base del servidor y concatena el URI proporcionado
    return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/SYS_CMP/' . ltrim($uri,'/');
}