<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Answers;

use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;
use RomanJertovsky\TgBotLibrarian\Tools;


class AnyMessage implements \RomanJertovsky\TgBotLibrarian\Telegram\iAnswer
{

    public static function index()
    {
        plog('AnyMessage called: ' . Receiver::getUsername() . ': '. Receiver::getText());

        Telegram::sendMessage([
            'text' => '<code>Don\'t know o_O</code>',
            'chat_id' => Receiver::getChatId()
        ]);

    }

}