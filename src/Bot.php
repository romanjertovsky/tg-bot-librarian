<?php

namespace RomanJertovsky\TgBotLibrarian;


/*
 * В этом классе - вся логика работы бота
 */
class Bot
{

    public function run(): void
    {

        // Подготовка
        $oTgPost        = new TgPost();
        $oLibrary       = new Library();
        $oMessageIn     = new MessageIn();
        $oMessageOut    = new MessageOut();
        $oSubscriber    = new Subscriber($oMessageIn->getUsername());


        if($oMessageIn->isCallback()) {
        // Если нажата inline-кнопка

            $sRoute = $oMessageIn->getCallbackData();
            $aArticle = $oLibrary->getArticleArray($sRoute);

            // Если статья премиум, а подписчик нет
            if(isset($aArticle['premium']) &&
                !$oSubscriber->isPremium()
            ) {

                $sIntro = $aArticle['intro'];

                // Перезапись массива статьи
                $aArticle = $oLibrary->getArticleArray('', 'msg_premium.json');
                $aArticle['text'] = $sIntro . PHP_EOL . PHP_EOL . $aArticle['text'];

            }

            $oMessageOut->setText($aArticle['text']);
            $oMessageOut->setImage($aArticle['image'] ?? null);

            $aDirTitles = $oLibrary->getDirTitles($sRoute);

            if($sRoute != '/') {
                $sBackWay = mb_substr($sRoute, 0,strripos($sRoute, '/'));
                $sBackWay = $sBackWay ?: '/';
                $aDirTitles[$sBackWay] = "⬅️ Назад";
            }

            $oMessageOut->setInlineKeyboardFromDirs($aDirTitles);


        } elseif ($oMessageIn->getText() === 'Главное меню 📋') {
        // Главное меню

            $aArticle = $oLibrary->getArticleArray('');

            $oMessageOut->setText($aArticle['text']);
            $oMessageOut->setImage($aArticle['image']);

            $aDirTitles = $oLibrary->getDirTitles('');

            $oMessageOut->setInlineKeyboardFromDirs($aDirTitles);

        } else {
        // Разбор остальных сообщений

            $oMessageOut->setText(
                $oLibrary->getArticleArray('', 'msg_welcome.json')['text']
            );

            $oMessageOut->setKeyboard([
                [
                    [
                    'text'  => 'Главное меню 📋'
                    ]
                ],
                [
                    [
                        'text'  => '⭐️ Hi-level доступ'
                    ],
                    [
                        'text'  => 'Создатели 👨‍💻',
                        'url' => 'https://YOUR/BUTTON/URL'
                    ],
                    [
                        'text'  => 'Обратная связь ✉️',
                        'url' => 'https://YOUR/BUTTON/URL'
                    ],
                ]
            ]);

        }

        $oMessageOut->setChatId($oMessageIn->getChatId());
        $oTgPost->sendMessage($oMessageOut);

    }


}