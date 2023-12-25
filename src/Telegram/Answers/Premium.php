<?php

namespace RomanJertovsky\TgBotLibrarian\Telegram\Answers;

use RomanJertovsky\TgBotLibrarian\Subscribers\Subscriber;
use RomanJertovsky\TgBotLibrarian\Telegram\Client;
use RomanJertovsky\TgBotLibrarian\Telegram\Receiver;
use RomanJertovsky\TgBotLibrarian\Telegram\Telegram;
use RomanJertovsky\TgBotLibrarian\Tools;


class Premium implements \RomanJertovsky\TgBotLibrarian\Telegram\iAnswer
{

    public static function index()
    {

        if(Subscriber::isPremium()) {

            Telegram::sendMessage([
                'chat_id' => Receiver::getChatId(),
                'text' => 'У вас уже есть премиум доступ!',
            ]);

        } else {

            Telegram::sendInvoice([
                'chat_id'           => Receiver::getChatId(),
                'title'             => 'Премиум подписка',
                'description'       => 'Бессрочный премиум-доступ к эксклюзивным статьям.',
                'payload'           => 'premium-1k',
                'provider_token'    => env('provider_token'),
                'currency'          => 'RUB',
                'prices'            => [[
                    'label'     => 'Руб',
                    'amount'    => 100000
                ]]
            ]);

        }

    }


    public static function pre_checkout_query()
    {

        // Пользователь уже премиум!
        if(Subscriber::isPremium()) {

            Telegram::answerPreCheckoutQuery([
                'pre_checkout_query_id' => Receiver::getMessageArray()['pre_checkout_query']['id'], // id платежа
                'ok'                    => false,
                'error_message'         => 'У вас уже есть премиум доступ!'
            ]);

            Telegram::sendMessage([
                'chat_id'   => Receiver::getChatId(),
                'text'      => 'У вас уже есть премиум доступ!',
            ]);

        } else {

            Telegram::answerPreCheckoutQuery([
                'pre_checkout_query_id' => Receiver::getMessageArray()['pre_checkout_query']['id'], // id платежа
                'ok'    => true
            ]);

            Subscriber::addPremium(Receiver::getChatId());

        }

    }


    public static function successful_payment()
    {

        plog("Successful payment from: " . Receiver::getUsername());

        Telegram::sendMessage([
            'chat_id'   => Receiver::getChatId(),
            'text'      => 'Поздравляем! С этого момента вам доступны все премиум статьи в библиотеке! 🔥',
            'reply_markup' => Tools::makeKeyboard(Start::mainKeyboardAssembly())
        ]);

    }

}