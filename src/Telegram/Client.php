<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;

class Client
{

    private string $response = '';

    public function TgPost(string $jsonMessage) {

        $sUrl = "https://api.telegram.org/bot". env('bot_token') ."/sendMessage";
        $oCurl = curl_init($sUrl);

        curl_setopt_array($oCurl, [
            CURLOPT_HEADER          => false,
            CURLOPT_POSTFIELDS      => $jsonMessage,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_HTTPHEADER      =>
                [
                    'Content-Type:application/json',
                    'Content-Length: ' . strlen($jsonMessage)
                ],
        ]);

        $this->response = curl_exec($oCurl);
        curl_close($oCurl);

    }


    public function getResponse(): string
    {
        return $this->response;
    }

    public function getResponseAsArray(): string
    {
        return json_decode($this->response, true);
    }

}