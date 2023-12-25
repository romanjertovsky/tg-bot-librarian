<?php

const INI_FILE = 'bot-test.ini';
require_once '../init.php';

plog('*log cleared*', ['append' => false]);

$oBot = new RomanJertovsky\TgBotLibrarian\Bot\Bot();


try {

    $oBot->run();

} catch (Throwable $exception) {

    $file = $exception->getFile();
    $line = $exception->getLine();
    $message = $exception->getMessage();
    plogErr("RUN EXCEPTION: $file:$line\n $message");

}


plog('END');
