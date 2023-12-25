<?php

const INI_FILE = 'bot-librarian.ini';
require_once '../init.php';
plog('START');

//plog('*log cleared*', ['append' => false]);

$oBot = new \RomanJertovsky\TgBotLibrarian\Bot\Bot();


try {

    $oBot->run();
    plog(\RomanJertovsky\TgBotLibrarian\Telegram\Receiver::getMessageArray());

} catch (Throwable $exception) {

    $file = $exception->getFile();
    $line = $exception->getLine();
    $message = $exception->getMessage();
    plogErr("RUN EXCEPTION: $file:$line\n $message");

}


plog('END');
