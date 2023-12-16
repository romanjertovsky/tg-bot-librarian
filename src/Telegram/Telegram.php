<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;


use RomanJertovsky\TgBotLibrarian\Telegram\Methods\{
    sendMessage,
    answerPreCheckoutQuery,
    sendInvoice,
    sendPhoto
};


class Telegram
{

    private static function Transport(iMethod $out, string $methodName): void
    {

        $Client = new Client();
        $Client->Post($out, $methodName);

        // TODO logs/errors, return?
        //  'ok' => false

    }


    public static function sendMessage(array $message): void
    {
        self::Transport(new sendMessage($message), __FUNCTION__);
    }


    public static function sendInvoice(array $message): void
    {
        self::Transport(new sendInvoice($message), __FUNCTION__);
    }


    public static function answerPreCheckoutQuery(array $message): void
    {
        self::Transport(new answerPreCheckoutQuery($message), __FUNCTION__);
    }


    public static function sendPhoto(int $chat_id, string $imagePath): void
    {
        self::Transport(new sendPhoto($chat_id, $imagePath), __FUNCTION__);
    }

}