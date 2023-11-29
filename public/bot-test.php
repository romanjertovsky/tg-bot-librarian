<?php

const INI_FILE = 'env_test.ini';
require_once '../init.php';

$oBot = new \RomanJertovsky\TgBotLibrarian\Bot();


try {

    $oBot->run();

} catch (Throwable $exception) {

    plogErr('run exception: ' . $exception->getMessage());

}


plog('END');
