<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Answers;

use RomanJertovsky\TgBotLibrarian\Library\Article;
use RomanJertovsky\TgBotLibrarian\Library\Catalog;
use RomanJertovsky\TgBotLibrarian\Subscribers\Subscriber;
use RomanJertovsky\TgBotLibrarian\Telegram\iAnswer;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;
use RomanJertovsky\TgBotLibrarian\Tools;

class AnswerArticle implements iAnswer
{

    public static function run()
    {

        $sRoute = Receiver::getCallbackData() ?? '/';

        $oArticle = new Article($sRoute);

        // Если статья премиум, а подписчик нет
        if(
            $oArticle->isPremium() &&
            !Subscriber::isPremium(Receiver::getUsername())
        ) {

            $oMsgPremiumArticle = new Article('', 'msg_premium.json');
            $sText = $oArticle->getIntro() . PHP_EOL . PHP_EOL . $oMsgPremiumArticle->getText();

        } else {

            $sText = $oArticle->getText();

        }

        $aDirTitles = Catalog::getDirTitles($sRoute);

        // Если статья не из корня, то добавляю в дерево кнопку "Назад"
        if($sRoute !== '/' && $sRoute !== '') {
            $sBackWay = mb_substr($sRoute, 0,strripos($sRoute, '/'));
            $sBackWay = $sBackWay ?: '/';
            $aDirTitles[$sBackWay] = "⬅️ Назад";
        }

        // Если статья с фото
        if(!empty($oArticle->getImage())) {
            Telegram::sendPhoto(
                Receiver::getChatId(),
                $oArticle->getImage()
            );
        }

        Telegram::sendMessage([
            'chat_id'   => Receiver::getChatId(),
            'text'      => $sText,
            'reply_markup' => Tools::makeInlineKeyboardFromDirs($aDirTitles)
        ]);


    }

}