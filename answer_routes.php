<?php

use RomanJertovsky\TgBotLibrarian\Bot\Router;
use RomanJertovsky\TgBotLibrarian\Telegram\Answers\Start;

/**
 * Router::add(['key1', 'key2', 'key3'], '', 'ClassAnswer1');
 * Router::add(['key1', 'key2', 'key3'], 'value', 'ClassAnswer2');
 * Router::add(['key1', 'key2', 'key3'], ['value1', 'value2'], 'ClassAnswer3');
 */


Router::add(['message', 'text'], Start::getMainMenu(), 'Start');
Router::add(['message', 'reply_to_message', 'text'], Start::getMainMenu()['feed_back_msg'], 'Start::Feedback');
Router::add(['callback_query', 'data'], '', 'AnswerArticle');
Router::add(['message'], '', 'AnyMessage');


