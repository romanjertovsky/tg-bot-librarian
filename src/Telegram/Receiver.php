<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;

class Receiver
{

    public static function getMessageArray(): array
    {
        static $messageArray = [];

        if(empty($messageArray))
            $messageArray = self::parseMessage();

        return $messageArray;

    }


    /**
     * Один из ключевых методов, получающий входящий запрос.
     * Если данных нет, или они некорректны, бот завершает работу.
     * @return array
     */
    private static function parseMessage(): array
    {

        $sInput = file_get_contents("php://input");

        if(empty($sInput)) {
            errorDie('Receiver::parseMessage empty message from Telegram');
        }

        $aMessage = json_decode($sInput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            errorDie('Receiver::parseMessage parseMessage JSON error: ' . json_last_error_msg());
        }

        return $aMessage;

    }


    /**
     * Метод определяет тип входящего сообщения
     * @return string|null - message|callback_query|pre_checkout_query|successful_payment
     */
    public static function getInType(): ?string
    {

        static $type = null;

        if(isset($type))
            return $type;

        $aMessage = self::getMessageArray();



        if (isset($aMessage['message'])) {
        // Обычное сообщение

            $type = 'message';

            // Если в сообщении данные об успешной оплате
            if(isset($aMessage['message']['successful_payment']))
                $type = 'successful_payment';


        } elseif(isset($aMessage['callback_query'])) {
        // Callback

            $type = 'callback_query';

        } elseif(isset($aMessage['pre_checkout_query'])) {
        // Пред-проверка оплаты (нажато "Заплатить")

            $type = 'pre_checkout_query';

        } else {

            plog([
                "Receiver::getInType: unknown message type!",
                '$aMessage' => $aMessage]);

        }

        return $type;

    }


    public static function isCallback2(): bool
    {
        if(self::getInType() === 'callback_query')
            return true;
        else
            return false;
    }


    public static function getCallbackData(): ?string
    {
        return match (self::getInType()) {
            'callback_query'    => self::getMessageArray()['callback_query']['data'],
            default             => null
        };
    }


    public static function getUsername(): ?string
    {
        return match (self::getInType()) {
            'callback_query'                => self::getMessageArray()['callback_query']['from']['username'] ?? null,
            'message', 'successful_payment' => self::getMessageArray()['message']['from']['username'] ?? null,
            'pre_checkout_query'            => self::getMessageArray()['pre_checkout_query']['from']['username'] ?? null,
            default                         => null,
        };
    }


    public static function getChatId(): ?string
    {
        return match (self::getInType()) {
            'callback_query'                => self::getMessageArray()['callback_query']['message']['chat']['id'] ?? null,
            'message', 'successful_payment' => self::getMessageArray()['message']['chat']['id'] ?? null,
            'pre_checkout_query'            => self::getMessageArray()['pre_checkout_query']['from']['id'] ?? null,
            default                         => null,
        };
    }


    public static function getText(): ?string
    {
        return match (self::getInType()) {
            'callback_query'    => self::getMessageArray()['callback_query']['message']['text'] ?? null,
            'message'           => self::getMessageArray()['message']['text'] ?? null,
            default             => null,
        };
    }


    /**
     * @deprecated нигде не используется
     * @return string|null
     */
    public static function getReplyToMessage(): ?string
    {
        return match (self::getInType()) {
            'message'           => self::getMessageArray()['message']['reply_to_message']['text']  ?? null,
            default             => null,
        };
    }

}