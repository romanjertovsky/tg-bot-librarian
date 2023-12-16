<?php

namespace RomanJertovsky\TgBotLibrarian\Bot;

use RomanJertovsky\TgBotLibrarian\Subscribers\Subscriber;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Answers;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;


class Bot
{

    public function run(): void
    {

        $className = Router::getCurrentRoute(Receiver::getMessageArray());

        plog("RUN: $className");

        Router::Starter($className);


    }

}