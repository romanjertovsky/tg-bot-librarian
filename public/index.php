<?php

require_once '../init.php';


plog('START (logCleared)', ['append' => false]);


//$oLibrary = new \RomanJertovsky\TgBotLibrarian\Message();

$oBot = new \RomanJertovsky\TgBotLibrarian\Bot();

try {
    $oBot->run();
} catch (Throwable $exception) {
    plogErr('run exception: ' . $exception->getMessage());
}



plog('END');

