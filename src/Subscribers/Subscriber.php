<?php

namespace RomanJertovsky\TgBotLibrarian\Subscribers;


use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;

class Subscriber
{

    private static array $userRow;

    /**
     * @param string|null $chat_id
     * @return bool
     */
    public static function isPremium(string $chat_id = null): bool
    {

        $chat_id = $chat_id ?? Receiver::getChatId();

        $aUserRow = self::getUserRow($chat_id);

        if(isset($aUserRow['username']))
            return true;
        else
            return false;

    }


    public static function getUserRow(string $chat_id): array
    {

        if(isset(self::$userRow))
            return self::$userRow;


        $dFile = fopen(DATA_DIR . env('premium_list'), 'r');

        self::$userRow = [];

        while (($aFileRow = fgetcsv($dFile, 4096, ';')) !== false) {

            // Первая строка - заголовки
            if(empty($headers)) {
                $headers = $aFileRow;
                continue;
            }

            // Если в csv не пропущен разделитель и кол-во заголовков = кол-ву полей
//                if(count($headers) === count($aFileRow))
            $row = array_combine($headers, $aFileRow);

            if($row['chat_id'] === $chat_id) {
                self::$userRow = $row;
                break;
            }

        }

        return self::$userRow;

    }


    /**
     * Добавить пользователя в список премиум-подписчиков
     * @param string $chat_id
     * @return bool - true - добавлен, false - уже был добавлен
     */
    public static function addPremium(string $chat_id): bool
    {

        if(isset(self::getUserRow($chat_id)['username']))
            return false;

        $user_name = Receiver::getUsername();

        file_put_contents(
            DATA_DIR . env('premium_list'),
            
            PHP_EOL .
            "$chat_id;$user_name;" .
            date('"Y-m-d H:i:s"'),

            FILE_APPEND
        );

        return true;

    }

    public static function getRegistrationDate() {

    }


}