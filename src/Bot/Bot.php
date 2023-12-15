<?php

namespace RomanJertovsky\TgBotLibrarian\Bot;

use RomanJertovsky\TgBotLibrarian\Subscribers\Subscriber;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Answers;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;


class Bot
{


    public function __construct()
    {
        require_once BASE_DIR . 'answer_routes.php';
    }


    public function run(): void
    {

        $className = Router::getCurrentRoute(Receiver::getMessageArray());
        $classPath = NS_PREFIX . "Telegram\Answers\\$className";
        $classPath();

    }

}