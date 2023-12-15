<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Methods;

use RomanJertovsky\TgBotLibrarian\Telegram\iMethod;


class sendPhoto implements iMethod
{

    private array $postField;


    public function __construct(int $chat_id, string $imagePath)
    {

        if(!file_exists($imagePath)) {
            plogErr("sendPhoto->__construct(), файл не существует: $imagePath");
            return;
        }

        $curlFile = curl_file_create(
            $imagePath,
            mime_content_type($imagePath),
            basename($imagePath));

        $this->postField = [
            'chat_id'   => $chat_id,
            'photo'     => $curlFile
        ];

    }


    public function getCurlOpts(): array
    {
        return [];
    }


    public function getPostField(): array|string
    {
        return $this->postField ?? [];
    }



}