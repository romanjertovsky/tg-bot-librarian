<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;

/**
 *
 */
class Client
{

    private string $response = '';


    public function Post(iMethod $oData, string $methodName): void
    {

        $sUrl = "https://api.telegram.org/bot". env('bot_token') ."/$methodName";

        $oCurl = curl_init($sUrl);

        curl_setopt_array($oCurl, [
            CURLOPT_POST            => true,
            CURLOPT_HEADER          => false,
            CURLOPT_POSTFIELDS      => $oData->getPostField(),
            CURLOPT_RETURNTRANSFER  => true]
            + $oData->getCurlOpts()
        );

        $this->response = curl_exec($oCurl);

        if(curl_errno($oCurl) !== 0)
            plogErr('Client->Post: curl_error: ' . curl_error($oCurl));

        curl_close($oCurl);

    }


    public function getResponse(): string
    {
        return $this->response;
    }


    public function getResponseAsArray(): array
    {

        try {
            $result = json_decode($this->response, true);
        } catch (JsonException $exception) {
            $result = ['Exception' => $exception->getMessage()];
        }

        return is_array($result) ? $result : [];

    }

}