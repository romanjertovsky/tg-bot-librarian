<?php

namespace RomanJertovsky\TgBotLibrarian\Bot;


class Router
{

    private static array $routes;


    public static function add(array $messageKeys, mixed $value = '', string $route = ''): void
    {

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

        if (empty(self::$routes))
            require_once BASE_DIR  . 'src/answer_routes.php';

        // Обход всех установленных маршрутов
        foreach (self::$routes as $curRoute) {

            $messageNested = $messageArray;

            // Проверка вложенного пути для текущего маршрута
            foreach ($curRoute['path'] as $key) {

                // Если во вложенном массиве существует следующий ключ
                if(is_array($messageNested) && array_key_exists($key, $messageNested)) {

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


    public static function Starter(?string $className): void
    {

        if(is_null($className))
            return;

        if(!strpos($className, '::'))
            $className .= '::index';

        $classPath = NS_PREFIX . "Telegram\Answers\\$className";

        if(!is_callable($classPath))
            plogErr("$classPath - route not found, shutdown");
        else
            $classPath();




    }


}