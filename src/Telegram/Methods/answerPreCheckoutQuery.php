<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Methods;

use RomanJertovsky\TgBotLibrarian\Telegram\iMethod;
use RomanJertovsky\TgBotLibrarian\Tools;


class answerPreCheckoutQuery implements iMethod
{

    private string $postField;


    public function __construct(array $message)
    {
        $message['parse_mode'] = env('parse_mode');
        $this->postField = Tools::json_encode($message);
    }

    public function getCurlOpts(): array
    {
        return
            [
                CURLOPT_HTTPHEADER => [
                    'Content-Type:application/json',
                    'Content-Length: ' . strlen($this->postField)
                ]
            ];
    }

    public function getPostField(): array|string
    {
        return $this->postField;
    }
}