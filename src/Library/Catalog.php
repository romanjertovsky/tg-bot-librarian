<?php

namespace RomanJertovsky\TgBotLibrarian\Library;


/**
 * Класс для работы с библиотекой.
 * В библиотеке каждый каталог представляет собой набор данных для генерации сообщения.
 *
 * Route - маршрут (для колбэков), каталоги разделены '/'
 * Например:
 * 00_food/avocado/avokado2
 * library/00_food/polezn_privichki/kletchatka
 *
 * Маршрут соответствует каталогу в файловой системе.
 *
 * Path - полный путь каталогу.
 * Всё отличие от Route, в том что путь полный, от корня ФС
 * а каталоги разделены и всегда заканчиваются 'DIRECTORY_SEPARATOR',
 * который может отличаться в разных ОС.
 *
 * Например:
 * C:\www\tg-bot-librarian\library\00_food\avocado\avokado2\
 * /var/www/bot-test.ru/library/00_food/avocado/avokado2/
 *
 */
class Catalog
{

    /**
     * Проверка существования пути по маршруту
     * Если передан только Route, то проверка каталога
     * Если передано и имя файла, то проверка существования файла
     * @param string $sRoute - маршрут
     * @param string|null $sFileName - имя файла по маршруту (msg.json)
     * @return bool
     */
    public static function isRouteExist(string $sRoute, string $sFileName = null): bool
    {
        $sPath = self::makeRoutePath($sRoute);

        if(is_null($sFileName))
            return is_dir($sPath);
        else
            return file_exists($sPath . $sFileName);
    }


    /**
     * Возвращает полный путь к каталогу сообщения из маршрута,
     * заменяя '/' разделителями каталогов
     * @param string $sRoute - маршрут/к/каталогу/сообщения (это НЕ путь к файлу)
     * @return string - 'LIB_DIR/путь/к/каталогу/'
     */
    public static function makeRoutePath(string $sRoute): string
    {
        $sRoute = trim($sRoute, '/');

        return
            LIB_DIR .
            str_replace('/', DIRECTORY_SEPARATOR,$sRoute) .
            (empty($sRoute) ? '' : DIRECTORY_SEPARATOR);

    }


    /**
     * @param string $sRoute
     * @return array
     */
    public static function getDirTitles(string $sRoute): array
    {

        $sRoute = trim($sRoute, '/');

        if(!self::isRouteExist($sRoute)) {
            plogErr("Catalog->getDirTitles: маршрут $sRoute не существует");
            return [];
        }

        $sRoutePath = self::makeRoutePath($sRoute);

        $aScanDir = scandir($sRoutePath);

        $aDirTitles = [];

        foreach ($aScanDir as $subDir) {

            if($subDir === '.' || $subDir === '..' || !is_dir($sRoutePath . $subDir))
                continue;

            $sSubRoute = "$sRoute/$subDir";
            $Article = new Article($sSubRoute);
            $aDirTitles[$sSubRoute] = $Article->getTitle();

        }

        return $aDirTitles;

    }

}