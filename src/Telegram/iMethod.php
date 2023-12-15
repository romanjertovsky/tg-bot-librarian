<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram;

/**
 * Интерфейс для класса Client
 */
interface iMethod
{


    public function getCurlOpts(): array;

    public function getPostField(): array|string;


}