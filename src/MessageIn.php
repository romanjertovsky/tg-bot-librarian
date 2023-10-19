<?php

namespace RomanJertovsky\TgBotLibrarian;


class MessageIn extends TgInData
{

    public function isCallback(): bool
    {
        if(isset($this->getMessageArray()['callback_query']))
            return true;
        else
            return false;
    }


    public function getCallbackData(): ?string
    {
        if($this->isCallback())
            return $this->getMessageArray()['callback_query']['data'];
        else
            return null;
    }


    public function getUsername(): string
    {
        if($this->isCallback())
            return $this->getMessageArray()['callback_query']['from']['username'];
        else
            return $this->getMessageArray()['message']['from']['username'];
    }


    public function getChatId():string
    {
        if($this->isCallback())
            return $this->getMessageArray()['callback_query']['message']['chat']['id'];
        else
            return $this->getMessageArray()['message']['chat']['id'];
    }


    public function getText(): string
    {
        if($this->isCallback())
            return $this->getMessageArray()['callback_query']['message']['text'];
        else
            return $this->getMessageArray()['message']['text'];
    }

}