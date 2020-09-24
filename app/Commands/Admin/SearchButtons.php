<?php

namespace App\Commands\Admin;

use App\Commands\BaseCommand;
use App\Services\TelegramKeyboard;

class SearchButtons extends BaseCommand {

    function processCommand($par = false)
    {
        TelegramKeyboard::addButton('ФИО', ['a' => 'search', 'search' => 'customer']);
        TelegramKeyboard::addButton('адресу', ['a' => 'search', 'search' => 'address']);
        TelegramKeyboard::addButton('номеру телефона', ['a' => 'search', 'search' => 'phone_number']);
        TelegramKeyboard::addButton('номеру заявки', ['a' => 'search', 'search' => 'id']);
        $this->tg->sendMessageWithInlineKeyboard('поиск по', TelegramKeyboard::get());
    }

}