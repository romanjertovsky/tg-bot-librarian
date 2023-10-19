<?php

namespace RomanJertovsky\TgBotLibrarian;

class Subscriber
{

    private ?string $username;
    private bool $isPremium;

    public function __construct(?string $username)
    {
        $this->username = $username;
    }


    /**
     * @return bool|null
     */
    public function isPremium(): ?bool
    {
        if(!isset($this->isPremium)) {
            $aSubsList = file(LIB_DIR . 'subs.csv', FILE_IGNORE_NEW_LINES);
            $this->isPremium = in_array($this->username, $aSubsList);
        }

        return $this->isPremium;

    }


}