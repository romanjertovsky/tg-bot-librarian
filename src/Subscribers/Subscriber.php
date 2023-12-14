<?php

namespace RomanJertovsky\TgBotLibrarian\Subscribers;


use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;

class Subscriber
{

    private static bool $isPremium;


    public static function isPremium(string $username = null): bool
    {
        $username = $username ?? Receiver::getUsername();

        if(!isset(self::$isPremium)) {

            $dFile = fopen(DATA_DIR . 'subscribers.csv', 'r');

            self::$isPremium = false;

            while (($aSubRow = fgetcsv($dFile, 1000, ';')) !== false) {

                if($aSubRow[0] === $username) {
                    self::$isPremium = true;
                    break;
                }

            }

        }

        return self::$isPremium;

    }


    public static function getRegistrationDate() {

    }


}