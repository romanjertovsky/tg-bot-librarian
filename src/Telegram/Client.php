<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;

/**
 *
 */
class Client
{

    private static string $response = '';
    private static string|array|null $postField = null;


    public function Post(iMethod $oData, string $methodName): void
    {

        self::$postField = $oData->getPostField();
        $sUrl = "https://api.telegram.org/bot". env('bot_token') ."/$methodName";

        $oCurl = curl_init($sUrl);

        curl_setopt_array($oCurl, [
            CURLOPT_POST            => true,
            CURLOPT_HEADER          => false,
            CURLOPT_POSTFIELDS      => $oData->getPostField(),
            CURLOPT_RETURNTRANSFER  => true]
            + $oData->getCurlOpts()
        );

        self::$response = curl_exec($oCurl);

        if(curl_errno($oCurl) !== 0)
            plogErr('Client->Post: curl_error: ' . curl_error($oCurl));

        curl_close($oCurl);

    }


    public static function getResponse(): string
    {
        return self::$response;
    }


    public static function getResponseAsArray(): array
    {

        try {
            $result = json_decode(self::$response, true);
        } catch (JsonException $exception) {
            $result = ['Exception' => $exception->getMessage()];
        }

        return is_array($result) ? $result : [];

    }

    public static function getCurPostField(): array
    {
        return [self::$postField];
    }


}