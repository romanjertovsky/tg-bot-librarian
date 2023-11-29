<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;


use RomanJertovsky\TgBotLibrarian\Telegram\Methods\{
    sendMessage,
    sendPhoto
};


class Telegram
{

    public function sendMessage(): sendMessage
    {
        return new sendMessage();
    }

    public function sendPhoto(): sendPhoto
    {
        return new sendPhoto();
    }
    
    
}