<?php

namespace RomanJertovsky\TgBotLibrarian;



class Bot
{

    public function run()
    {

        // Получение сообщения
        $oMessageIn     = new ParseIn();
        $oTgPost        = new TgPost();
        $oMessage       = new Message();
        $oLibrary       = new Library();

        $oMessage->setChatId($oMessageIn->getChatId());
        plog($oMessageIn->getMessageArray());

        if($oMessageIn->isCallback()) {
        // Если нажата inline-кнопка

            $sRoute = $oMessageIn->getCallbackData();

            $aArticle = $oLibrary->getArticleArray($sRoute);
            $oMessage->setText($aArticle['text']);
            $oMessage->setImage($aArticle['image']);

            $aDirTitles = $oLibrary->getDirTitles($sRoute);

            if($sRoute != '/') {
                $sBackWay = mb_substr($sRoute, 0,strripos($sRoute, '/'));
                $sBackWay = $sBackWay ?: '/';
                $aDirTitles[$sBackWay] = "⬅️ Назад";
            }

            $oMessage->setInlineKeyboardFromDirs($aDirTitles);


        } elseif ($oMessageIn->getText() === 'Главное меню 📋') {
        // Главное меню

//            $oTgPost->test($oMessageIn->getChatId());
//            die();;


            $aArticle = $oLibrary->getArticleArray('');

            $oMessage->setText($aArticle['text']);
            $oMessage->setImage($aArticle['image']);

            $aDirTitles = $oLibrary->getDirTitles('');

            $oMessage->setInlineKeyboardFromDirs($aDirTitles);

        } else {
        // Разбор остальных сообщений

            $a =
            [
                [
                    [
                        'text' => 'Тестовая кнопка 1',
                        'url' => 'YOUR BUTTON URL',
                    ],
                    [
                        'text' => 'Тестовая кнопка 2',
                        'url' => 'YOUR BUTTON URL',
                    ],
                ]
            ];

            $oMessage->setText($oLibrary->getWelcome());
            $oMessage->setKeyboard([
                [
                    [
                    'text'  => 'Главное меню 📋'
                    ]
                ],
                [[
                    'text'  => '⭐️ Стать роботом'
                ],
                [
                    'text'  => 'Создатели 👨‍🏫👨‍💻',
                    'url' => 'https://YOUR/BUTTON/URL'
                ],]
            ]);

        }

        $oTgPost->sendMessage($oMessage);

    }


}