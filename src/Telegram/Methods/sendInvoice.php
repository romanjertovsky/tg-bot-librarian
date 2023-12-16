<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Methods;

use RomanJertovsky\TgBotLibrarian\Telegram\iMethod;
use RomanJertovsky\TgBotLibrarian\Tools;


class sendInvoice implements iMethod
{

    private string $postField;


    public function __construct(array $message)
    {
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