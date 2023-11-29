<?php

define("F_START_TIME", microtime(true));             // Время выполнения
const BASE_DIR = __DIR__ . DIRECTORY_SEPARATOR;             // Базовая директория
const LIB_DIR = BASE_DIR . 'library' . DIRECTORY_SEPARATOR; // Директория библиотеки

header('Content-Type: application/json; charset=utf-8');

require_once 'helpers.php';


if(env('log_write')) {
    ini_set('log_errors', 1);
    ini_set(
        'error_log',
        BASE_DIR .
        'log' . DIRECTORY_SEPARATOR .
        date('Y-m-d') .
        '_' .
        env('log_err_postfix') .
        '.log');
}


if (env('debug')) {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

} else {

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

}

