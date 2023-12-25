<?php

define("F_START_TIME", microtime(true));                 // Время начала выполнения
date_default_timezone_set(env('default_timezone'));

const BASE_DIR  = __DIR__ . DIRECTORY_SEPARATOR;                // Базовая директория
const DATA_DIR  = BASE_DIR . 'data' . DIRECTORY_SEPARATOR;      // Списки подписчиков
const ENV_DIR   = BASE_DIR . 'env' . DIRECTORY_SEPARATOR;       // Директория с настройками
const LIB_DIR   = BASE_DIR . 'library' . DIRECTORY_SEPARATOR;   // Директория библиотеки
const LOG_DIR   = BASE_DIR . 'log' . DIRECTORY_SEPARATOR;       // Директория для логов
const NS_PREFIX = 'RomanJertovsky\TgBotLibrarian\\';            // Префикс namespace

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



// Автозагрузчик для классов в src/
spl_autoload_register(function ($sClassName) {

    // Использует ли запрошенный класс префикс пространства имён?
    $iPrefixLen = strlen(NS_PREFIX);
    if (strncmp(NS_PREFIX, $sClassName, $iPrefixLen) !== 0) {
        // Нет, этот автозагрузчик не подходит
        return;
    }

    // Относительное имя класса, без префикса
    $sRelativeClassName = substr($sClassName, $iPrefixLen);

    // Полный путь к файлу запрашиваемого класса
    // с заменой разделителей в пространстве имён на разделители каталогов,
    // добавление расширений
    $sClassFile =
        BASE_DIR .
        'src' . DIRECTORY_SEPARATOR .
        str_replace('\\', DIRECTORY_SEPARATOR, $sRelativeClassName) .
        '.php';

    if(file_exists($sClassFile))
        require_once $sClassFile;

});