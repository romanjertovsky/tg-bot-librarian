<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Methods;

use RomanJertovsky\TgBotLibrarian\Tools;
use RomanJertovsky\TgBotLibrarian\Telegram\{
    iMethod,
    Client
};


class sendMessage implements iMethod
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


    public function getPostField(): string
    {
        return $this->postField;
    }

}