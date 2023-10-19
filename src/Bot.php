<?php

namespace RomanJertovsky\TgBotLibrarian;


/*
 * В этом классе - вся логика работы бота
 */
class Bot
{

    public function run()
    {

        // Подготовка
        $oTgPost        = new TgPost();
        $oLibrary       = new Library();
        $oMessageIn     = new MessageIn();
        $oMessageOut    = new MessageOut();


        if($oMessageIn->isCallback()) {
        // Если нажата inline-кнопка

            $sRoute = $oMessageIn->getCallbackData();

            $aArticle = $oLibrary->getArticleArray($sRoute);
            $oMessageOut->setText($aArticle['text']);
            $oMessageOut->setImage($aArticle['image']);

            $aDirTitles = $oLibrary->getDirTitles($sRoute);

            if($sRoute != '/') {
                $sBackWay = mb_substr($sRoute, 0,strripos($sRoute, '/'));
                $sBackWay = $sBackWay ?: '/';
                $aDirTitles[$sBackWay] = "⬅️ Назад";
            }

            $oMessageOut->setInlineKeyboardFromDirs($aDirTitles);


        } elseif ($oMessageIn->getText() === 'Главное меню 📋') {
        // Главное меню

//            $oTgPost->test($oMessageIn->getChatId());
//            die();;


            $aArticle = $oLibrary->getArticleArray('');

            $oMessageOut->setText($aArticle['text']);
            $oMessageOut->setImage($aArticle['image']);

            $aDirTitles = $oLibrary->getDirTitles('');

            $oMessageOut->setInlineKeyboardFromDirs($aDirTitles);

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

            $oMessageOut->setText($oLibrary->getWelcome());
            $oMessageOut->setKeyboard([
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

        $oMessageOut->setChatId($oMessageIn->getChatId());
        $oTgPost->sendMessage($oMessageOut);

    }


}