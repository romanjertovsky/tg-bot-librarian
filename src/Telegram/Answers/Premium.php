<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Answers;

use RomanJertovsky\TgBotLibrarian\Telegram\Client;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;


class Premium implements \RomanJertovsky\TgBotLibrarian\Telegram\iAnswer
{

    public static function index()
    {
        plog('Premium::index called!!!');
        Telegram::sendInvoice([
            'chat_id'           => Receiver::getChatId(),
            'title'             => 'Премиум подписка',
            'description'       => 'Бессрочный премиум-доступ к эксклюзивным статьям.',
            'payload'           => 'MyPayload',
            'provider_token'    => '381764678:TEST:71260',
            'currency'          => 'RUB',
            'prices'            => [[
                'label'     => 'Руб',
                'amount'    => 100000
            ]]
        ]);

//        plog(Client::getCurPostField());
//        plog(Client::getResponseAsArray());

    }


    public static function pre_checkout_query()
    {
        plog('Premium::pre_checkout_query called!!!');
        $test = [
            'update_id' => 466925506,
            'pre_checkout_query' =>
                [
                    'id' => '613037826718370318',
                    'from' =>
                        [
                            'id' => 142733991,
                            'is_bot' => false,
                            'first_name' => 'NaN',
                            'username' => 'NewArtist',
                            'language_code' => 'ru',
                        ],
                    'currency' => 'RUB',
                    'total_amount' => 100000,
                    'invoice_payload' => 'MyPayload',
                ],
        ];
        
        $messageArray = Receiver::getMessageArray();
        Telegram::answerPreCheckoutQuery([
            'pre_checkout_query_id' => $messageArray['pre_checkout_query']['id'],
            'ok'    => true
        ]);
    }


    public static function successful_payment()
    {
        plog('Premium::successful_payment called!!!');
        $test =  [
            'update_id' => 466925509,
            'message' =>
                 [
                    'message_id' => 3634,
                    'from' =>
                         [
                            'id' => 142733991,
                            'is_bot' => false,
                            'first_name' => 'NaN',
                            'username' => 'NewArtist',
                            'language_code' => 'ru',
                        ],
                    'chat' =>
                         [
                            'id' => 142733991,
                            'first_name' => 'NaN',
                            'username' => 'NewArtist',
                            'type' => 'private',
                        ],
                    'date' => 1702758861,
                    'successful_payment' =>
                         [
                            'currency' => 'RUB',
                            'total_amount' => 100000,
                            'invoice_payload' => 'MyPayload',
                            'telegram_payment_charge_id' => '6305657289_142733991_153587_7313287314807418228',
                            'provider_payment_charge_id' => '2d101d8b-000f-5000-9000-10cbe87c9bc2',
                        ],
                ],
        ];

        plog("Successful payment from: " . Receiver::getUsername());
        
    }

}