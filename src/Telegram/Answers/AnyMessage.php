<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Answers;

use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;


class AnyMessage implements \RomanJertovsky\TgBotLibrarian\Telegram\iAnswer
{

    public static function run()
    {
        plog('AnyMessage called: ' . Receiver::getUsername() . ': '. Receiver::getText());
    }

}