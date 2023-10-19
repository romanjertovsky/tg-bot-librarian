<?php

namespace RomanJertovsky\TgBotLibrarian;


class TgPost
{


    public function sendMessage(Message $Message): void
    {

        $sImage = $Message->getImage();
        if(!is_null($sImage)) {
            $this->tgSendPhoto($sImage, $Message->getChatId());
        }

        $aMessage = $Message->getMessageArray();
        $jsonMessage = $this->prepareJson($aMessage);
        $this->tgSendMessage($jsonMessage);

    }


    private function prepareJson(array $MessageArray): string
    {

        $MessageArray['parse_mode'] = 'markdown';

        $jsonMessage = json_encode(
            $MessageArray,
            JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

        return $jsonMessage;

    }


    private function tgSendMessage(string $jsonMessage): string
    {

        $sUrl = "https://api.telegram.org/bot". env('bot_token') ."/sendMessage";
        $oCurl = curl_init($sUrl);

        curl_setopt_array($oCurl, [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_POSTFIELDS      => $jsonMessage,
            CURLOPT_HTTPHEADER      =>
                [
                    'Content-Type:application/json',
                    'Content-Length: ' . strlen($jsonMessage)
                ],
        ]);

        $sResult = curl_exec($oCurl);
        curl_close($oCurl);

        $aResult = json_decode($sResult, true);

        if(!$aResult['ok']) {

            plogErr('message: ' . $jsonMessage);
            plogErr('result: ' . $sResult);
        }


        return $sResult;

    }


    private function tgSendPhoto(string $sImagePath, int $chat_id)
    {
        $sUrl = "https://api.telegram.org/bot". env('bot_token') ."/sendPhoto";

        $mimeType = mime_content_type($sImagePath);

        $messageArray = [
            'chat_id' => $chat_id,
            'photo' => curl_file_create($sImagePath, $mimeType , 'image.jpg')
        ];

        $oCurl = curl_init($sUrl);

        curl_setopt_array($oCurl, [
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $messageArray,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false
        ]);

        $sResult = curl_exec($oCurl);
        curl_close($oCurl);

    }


}