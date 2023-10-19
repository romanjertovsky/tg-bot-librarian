<?php

namespace RomanJertovsky\TgBotLibrarian;

abstract class TgInData
{

    private static array $aInData = [];

    abstract public function isCallback(): bool;
    abstract public function getCallbackData(): ?string;
    abstract public function getUsername(): ?string;
    abstract public function getChatId(): ?string;
    abstract public function getText(): ?string;

    private array $aTestMessage =
        [
            'update_id' => '166398948',
            'message' =>
                [
                    'message_id' => '15',
                    'from' =>
                        [
                            'id' => '142733991',
                            'is_bot' => '',
                            'first_name' => 'NaN',
                            'username' => 'NewArtist',
                            'language_code' => 'ru'
                        ],
                    'chat' =>
                        [
                            'id' => '142733991',
                            'first_name' => 'NaN',
                            'username' => 'NewArtist',
                            'type' => 'private'
                        ],
                    'date' => '1689022126',
                    'text' => 'Message text!!!',
                ]
        ];

    private array $aTestCallback =
        [
            'update_id' => 166399070,
            'callback_query' =>
                [
                    'id' => 613037827235879798,

                    'from' =>
                        [
                            'id' => 142733991,
                            'is_bot' => '',
                            'first_name' => 'NaN',
                            'username' => 'NewArtist',
                            'language_code' => 'ru',
                        ],

                    'message' =>
                        [
                            'message_id' => 186,
                            'from' =>
                                [
                                    'id' => 6091080579,
                                    'is_bot' => 1,
                                    'first_name' => 'Corvin trainer',
                                    'username' => 'Corvin_trainer_bot',
                                ],

                            'chat' =>
                                [
                                    'id' => 142733991,
                                    'first_name' => 'NaN',
                                    'username' => 'NewArtist',
                                    'type' => 'private',
                                ],

                            'date' => 1692651522,
                            'text' => 'Привет!!',
                            'reply_markup' =>
                                [
                                    'inline_keyboard' => '...'
                                ]
                        ],

                    'chat_instance' => -8698255000789808819,
                    'data' => 'aaaa'
                ]

        ];


    public function getMessageArray(): array
    {

        if(empty(self::$aInData))
            self::$aInData = $this->parseMessage();

        return self::$aInData;

    }


    protected function parseMessage(): array
    {

//        return $this->aTestMessage;;
//        return $this->aTestCallback;;

        $sInput = file_get_contents("php://input");

        if(empty($sInput)) {
            plogErr('parseMessage error: empty message from Telegram');
            errorDie('Input error');
        }

        $aMessage = json_decode($sInput, true);

        if ($aMessage === null && json_last_error() !== JSON_ERROR_NONE) {
            plogErr('parseMessage JSON error: ' . json_last_error_msg());
            errorDie('JSON error');
        }

        return $aMessage;

    }


}