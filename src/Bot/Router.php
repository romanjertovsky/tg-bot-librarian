<?php

namespace RomanJertovsky\TgBotLibrarian\Bot;

use RomanJertovsky\TgBotLibrarian\Telegram\Answers\Start;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;


class Router
{

    private static array $routes;


    public static function add(array $messageKeys, mixed $value = '', string $route = ''): void
    {

        if(!strpos($route, '::'))
            $route .= '::run';

        self::$routes[] = [
            'path' => $messageKeys,
            'value' => $value,
            'route' => $route
        ];

    }


    /**
     * Определение класса Telegram/Answers/...
     * по структуре и содержимому входящего сообщения
     * @param array $messageArray
     * @return string|null
     */
    public static function getCurrentRoute(array $messageArray): ?string
    {

        // Обход всех установленных маршрутов
        foreach (self::$routes as $curRoute) {

            $messageNested = $messageArray;

            // Проверка вложенного пути для текущего маршрута
            foreach ($curRoute['path'] as $key) {

                if(array_key_exists($key, $messageNested)) {

                    // вложенный ключ существует
                    $messageNested = $messageNested[$key];

                } else {

                    // вложенного ключа в этом сообщении нет, переход к следующему маршруту
                    continue 2;

                }

            }


            // Путь по ключам существует,
            // проверка значения
            if(empty($curRoute['value'])) {

                // Проверка по значению не нужна
                // Маршрут найден!
                return $curRoute['route'];

            } else {

                // Проверка по значению (без учёта регистра)
                // если передан массив или строка
                if(is_array($curRoute['value'])) {

                    // Значение в массиве подошло: маршрут найден!
                    foreach ($curRoute['value'] as $value)
                        if(strcasecmp($value, $messageNested) === 0)
                            return $curRoute['route'];

                } else {

                    // Значение в строке подошло: маршрут найден!
                    if(strcasecmp($curRoute['value'], $messageNested) === 0)
                        return $curRoute['route'];

                }

            }

        }

        // Конец обхода всех маршрутов
        // Ни один маршрут к сообщению не подошёл!

        return null;

    }


}