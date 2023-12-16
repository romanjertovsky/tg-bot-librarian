<?php

use RomanJertovsky\TgBotLibrarian\Bot\Router;
use RomanJertovsky\TgBotLibrarian\Telegram\Answers\Start;

/**
 * Router::add(['key1', 'key2', 'key3'], '', 'ClassAnswer1');
 * Router::add(['key1', 'key2', 'key3'], 'value', 'ClassAnswer2');
 * Router::add(['key1', 'key2', 'key3'], ['value1', 'value2'], 'ClassAnswer3');
 */


// Любое сообщение из Старт-меню + /start
Router::add(['message', 'text'], Start::getMainMenu(), 'Start');

// Ответ на feed_back_msg
Router::add(['message', 'reply_to_message', 'text'], Start::getMainMenu()['feed_back_msg'], 'Start::Feedback');

//Любой колбэк
Router::add(['callback_query', 'data'], '', 'AnswerArticle');

// Премиум подписка
Router::add(['message', 'text'], '/getPremium', 'Premium');

// Подтверждение оплаты подписки
Router::add(['pre_checkout_query'], '', 'Premium::pre_checkout_query');

// Успешная оплата
Router::add(['message', 'successful_payment'], '', 'Premium::successful_payment');

// Любое сообщение
Router::add(['message'], '', 'AnyMessage');


