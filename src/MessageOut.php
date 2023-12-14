<?php

namespace RomanJertovsky\TgBotLibrarian;

/**
 * @deprecated
 */
class MessageOut
{

    private int     $chat_id;
    private string  $text = '';
    private array   $reply_markup;   // Невозможно передавать и keyboard и inline_keyboard
    private ?string $image = null;


    public function getMessageArray(): array
    {

        $messageArray = [
            'chat_id'   => $this->chat_id ?? '',
            'text'      => $this->text,
        ];

        if(!empty($this->reply_markup))
            $messageArray['reply_markup'] = $this->reply_markup;

        return $messageArray;

    }

    public function setImage($imagePath = ''): void
    {
        $this->image = $imagePath;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setChatId(int $chat_id): void
    {
        $this->chat_id = $chat_id;
    }

    public function getChatId(): int
    {
        return $this->chat_id;
    }


    public function setText(string $text): void
    {
        $this->text = $text;
    }


    public function setKeyboard(array $buttons): void
    {

        $this->reply_markup =
            [
                'keyboard' => $buttons,
                'one_time_keyboard' => false,
                'resize_keyboard' => true,
            ];

    }


    /**
     * @param array $aDirArray ['index' => 'Dir Header']
     * @return void
     */
    public function setInlineKeyboardFromDirs(array $aDirArray): void
    {

        $inlineKeyboard = [];

        foreach ($aDirArray as $key => $value) {

            $inlineKeyboard[] = [[
                'text'          => $value,
                'callback_data' => $key
            ]]; // вложенный массив - строка

        }

        $this->reply_markup =
            [
                'inline_keyboard' => $inlineKeyboard
            ];

    }


    public function setInlineKeyboard(array $buttons): void
    {

        $this->reply_markup =
            [
                'inline_keyboard' => $buttons
            ];

    }


}