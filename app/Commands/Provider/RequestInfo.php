<?php

namespace App\Commands\Provider;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\TelegramKeyboard;

class RequestInfo extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->tg_parser::getCallbackByKey('available') || $par) {
            $id = $this->tg_parser::getCallbackByKey('id') ?: $par;
            $request = Request::find($id);
            if ($request) {
                $text = '<b>id: </b>' . $request->id . "\n";
                $text .= '<b>Адресс: </b>' . $request->address . "\n";
                $text .= '<b>Категория: </b>' . $request->category->title . "\n";
                $text .= '<b>ФИО: </b>' . $request->customer . "\n";
                if ($request->category_id == 4) {
                    $text .= 'hererer';
                    $text .= '<b>Дополнительный комментарий: </b>' . $request->sum . "\n";
                } else {
                    $text .= '<b>Сумма покупки: </b>' . $request->sum . "\n";
                    $text .= '<b>Список нужных товаров: </b>' . $request->products . "\n" . "\n";
                }

                if ($request->provider_id == $this->user->id) {
                    TelegramKeyboard::addButton('✅ подтвердить', ['a' => 'confirm_request', 'id' => $request->id]);
                } else {
                    TelegramKeyboard::addButton('✅ принять', ['a' => 'take_request', 'id' => $request->id]);
                }
                TelegramKeyboard::addButton('❌ отказаться', ['a' => 'cancel_request', 'id' => $request->id]);

                $this->tg->sendMessageWithInlineKeyboard($text, TelegramKeyboard::get());
            }
        }
    }
}