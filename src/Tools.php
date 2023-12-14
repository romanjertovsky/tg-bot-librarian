<?php

namespace RomanJertovsky\TgBotLibrarian;

class Tools
{

    public static function json_encode(array $array): string
    {

        return json_encode(
            $array,
            JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

    }


    public static function makeKeyboard(
        array $keyboard,
        bool $one_time_keyboard = false,
        bool $resize_keyboard = true
    ): array
    {

        return [
            'keyboard' => $keyboard,
            'one_time_keyboard' => $one_time_keyboard,
            'resize_keyboard' => $resize_keyboard,
        ];

    }

    public static function makeInlineKeyboardFromDirs(array $aDirArray): array
    {
        $inline_keyboard = [];

        foreach ($aDirArray as $key => $value) {
            $inline_keyboard[] = [[
                'text'          => $value,
                'callback_data' => $key
            ]]; // вложенный массив - строка кнопок
        }
        return ['inline_keyboard' => $inline_keyboard];
    }

}