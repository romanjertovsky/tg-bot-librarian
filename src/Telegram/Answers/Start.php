<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Answers;

use RomanJertovsky\TgBotLibrarian\Library\Article;
use RomanJertovsky\TgBotLibrarian\Telegram\iAnswer;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;
use RomanJertovsky\TgBotLibrarian\Tools;

class Start implements iAnswer
{

    private static array $mainMenuMsg = [
        'main_menu' => 'Главное меню 📋',
        'premium'   => '⭐️ Hi-level доступ',
        'creators'  => 'Создатели 👨‍💻',
        'feed_back' => 'Обратная связь ✉️',
        'feed_back_msg' => 'Чтобы оставить отзыв, напишите ответ на это сообщение... ⬅️',
        'start' => '/start'
    ];


    public static function getMainMenu(): array
    {
        return self::$mainMenuMsg;
    }


    public static function index()
    {

        switch (Receiver::getText()) {

            case '/start':
                $Article = new Article('/', 'msg_welcome.json');
                $text = $Article->getText();
                break;

            case self::$mainMenuMsg['main_menu']:
                AnswerArticle::index();
                return;

            case self::$mainMenuMsg['premium']:
                $Article = new Article('/', 'msg_premium.json');
                $text = $Article->getText();
                break;

            case self::$mainMenuMsg['creators']:
                $Article = new Article('/', 'msg_creators.json');
                $text = $Article->getText();
                break;

            case self::$mainMenuMsg['feed_back']:
                $text = self::$mainMenuMsg['feed_back_msg'];
                break;

            default:
                $text = '`Don\'t know o_O`';
                break;

        }

        // Если статья с фото
        if(isset($Article) && !empty($Article->getImage())) {
            Telegram::sendPhoto(
                Receiver::getChatId(),
                $Article->getImage()
            );
        }

        // Текстовое сообщение
        Telegram::sendMessage([
            'text' => $text ?? 'no text',
            'chat_id' => Receiver::getChatId(),
            'reply_markup' => Tools::makeKeyboard(self::mainKeyboardAssembly())
        ]);

    }


    public static function Feedback()
    {
        plog(Receiver::getUsername() .
            ': ' . Receiver::getText(), [
            'postfix'   => 'feedback',
            'write' => true]);

        Telegram::sendMessage([
            'text' => 'Спасибо, ваше сообщение передано администратору! ✌️',
            'chat_id' => Receiver::getChatId(),
        ]);
    }


    private static function mainKeyboardAssembly(): array
    {

        return [
            [
                [
                    'text'  => self::$mainMenuMsg['main_menu']
                ]
            ],
            [
                [
                    'text'  => self::$mainMenuMsg['premium']
                ],
                [
                    'text'  => self::$mainMenuMsg['creators'],
                ],
                [
                    'text'  => self::$mainMenuMsg['feed_back'],
                ],
            ]
        ];

    }


}