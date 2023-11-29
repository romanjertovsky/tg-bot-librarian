<?php

use JetBrains\PhpStorm\NoReturn;


/**
 * Доступ к значениям файла конфигурации env.ini
 * @param string $key - ключ
 * @param string|null $default - значение, если ключа не найдено
 * @return string|null
 */
function env(string $key, string $default = null): ?string {

    static $ENV_CONFIG = [];    // Загруженные настройки

    if(empty($ENV_CONFIG)) {

        $iniFilePath =
            BASE_DIR . 'env/' .
            (defined('INI_FILE') ? INI_FILE : 'env.ini');

        if(file_exists($iniFilePath))
            $ENV_CONFIG = parse_ini_file($iniFilePath, false, INI_SCANNER_TYPED);
        else
            errorDie('ini file doesnt exist');

    }

    if(array_key_exists($key, $ENV_CONFIG))
        return $ENV_CONFIG[$key];

    elseif (isset($default))
        return $default;

    else
        return null;

}



/** Возвращает строку со временем генерации с момента запуска
 * @return string
 */
function now(): string {
    return number_format((microtime(true) - F_START_TIME), 3, '.');
}



/**
 * Глобальная функция plog() - (от print log) логирование|вывод на экран
 * @param string|array $Record - строка для вывода и/или записи
 * @param array $configRewrite - перезапись глобальных настроек из env
 * Возможные элементы:
 * bool     'write'     => true
 * bool     'print'     => true
 * string   'postfix'   => 'logName'
 * bool     'append'   => 'true'
 * @return void
 */
function plog(mixed $Record, array $configRewrite = []): void {

    $bWorkLogWrite = $configRewrite['write']    ?? env('log_write');
    $bWorkLogPrint = $configRewrite['print']    ?? env('log_print');
    $sLogFileName  = $configRewrite['postfix']  ?? env('log_postfix');
    $bFileAppend   = $configRewrite['append']   ?? true;

    if(is_bool($Record))
        $Record = var_export($Record, true);

    $sLogRow =
        date('[d.m.Y H:i:s ') . now() . '] ' .
        (is_array($Record) ? print_r($Record,true) : $Record) .
        PHP_EOL;

    if($bWorkLogPrint)
        print $sLogRow;

    if($bWorkLogWrite && !empty($sLogFileName)) {

        $logDir = BASE_DIR . 'log' . DIRECTORY_SEPARATOR;

        if (!file_exists($logDir) && !is_dir( $logDir ))
            mkdir( $logDir );

        $sLogPath =
            $logDir .
            date('Y-m-d') .
            '_' .
            $sLogFileName .
            '.log';

        $flags = 0;

        if($bFileAppend)
            $flags = FILE_APPEND;

        file_put_contents($sLogPath, $sLogRow, $flags);

    }

}


/**
 * Функция предназначена для сокращения вызова plog для отдельного лога ошибок
 * Параметры log_write и log_print действуют
 * @param mixed $Record - строка для вывода и/или записи
 * @return void
 */
function plogErr(mixed $Record): void {

    plog($Record, ['postfix' => env('log_err_postfix')]);

}



// Автозагрузчик для классов в src/
spl_autoload_register(function ($sClassName) {

    // Префикс пространства имён для данного проекта
    $sPrefix = 'RomanJertovsky\\TgBotLibrarian';

    // Использует ли запрошенный класс префикс пространства имён?
    $iPrefixLen = strlen($sPrefix);
    if (strncmp($sPrefix, $sClassName, $iPrefixLen) !== 0) {
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



/**
 * @param string $sMessage - {"error": "sMessage"}
 * @param int $response_code - 400 Bad Request
 * @return void
 */
#[NoReturn] function errorDie(string $sMessage = '', int $response_code = 400): void
{

    http_response_code($response_code);
    die(json_encode(['error' => $sMessage]));

}