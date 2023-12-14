<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;

class Receiver
{

    private static array $aInData = [];


    public static function getMessageArray(): array
    {

        if(empty(self::$aInData))
            self::$aInData = self::parseMessage();

        return self::$aInData;

    }


    private static function parseMessage(): array
    {

        $sInput = file_get_contents("php://input");

        if(empty($sInput))
            plogErr('Receiver::parseMessage error: empty message from Telegram');

        $aMessage = json_decode($sInput, true);

        if (json_last_error() !== JSON_ERROR_NONE)
            plogErr('parseMessage JSON error: ' . json_last_error_msg());

        // TODO проверка корректности $aMessage
        return $aMessage;

    }


    public static function isCallback(): bool
    {
        if(isset(self::getMessageArray()['callback_query']))
            return true;
        else
            return false;
    }


    public static function getCallbackData(): ?string
    {
        if(self::isCallback())
            return self::getMessageArray()['callback_query']['data'];
        else
            return null;
    }


    public static function getUsername(): string
    {
        if(self::isCallback())
            return self::getMessageArray()['callback_query']['from']['username'];
        else
            return self::getMessageArray()['message']['from']['username'];
    }


    public static function getChatId(): ?string
    {
        if(self::isCallback())
            return self::getMessageArray()['callback_query']['message']['chat']['id'];
        else
            return self::getMessageArray()['message']['chat']['id'];
    }


    public static function getText(): ?string
    {
        if(self::isCallback())
            return self::getMessageArray()['callback_query']['message']['text'];
        else
            return self::getMessageArray()['message']['text'];
    }


    public static function getReplyToMessage(): bool|string
    {
        return self::getMessageArray()['message']['reply_to_message']['text'] ?? false;
    }


}