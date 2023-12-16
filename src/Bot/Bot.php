<?php

namespace RomanJertovsky\TgBotLibrarian\Bot;

use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;


class Bot
{

    public function run(): void
    {

        $className = Router::getCurrentRoute(Receiver::getMessageArray());

        plog("RUN: $className");

        Router::Starter($className);


    }

}