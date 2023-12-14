<?php

const INI_FILE = 'env.ini';
require_once '../init.php';


$oBot = new \RomanJertovsky\TgBotLibrarian\Bot\Bot();


try {

    $oBot->run();

} catch (Throwable $exception) {

    $file = $exception->getFile();
    $line = $exception->getLine();
    $message = $exception->getMessage();
    plogErr("RUN EXCEPTION: $file:$line\n $message");

}


plog('END');
