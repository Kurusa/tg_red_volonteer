<?php

namespace App\Commands\Operator;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;

class RequestInfo extends BaseCommand {

    function processCommand($par = false)
    {
        $request = Request::find($this->tg_parser::getCallbackByKey('id'));
        if ($request) {
            $text = '<b>id: </b>' . $request->id . "\n";
            $text .= '<b>Адресс: </b>' . $request->address . "\n";
            $text .= '<b>Категория: </b>' . $request->category->title . "\n";
            $text .= '<b>ФИО: </b>' . $request->customer . "\n";
            $text .= '<b>Номер телефона: </b>' . $request->catergory_id . "\n";
            if ($request->category_id == 4) {
                $text .= '<b>Дополнительный комментарий: </b>' . $request->sum . "\n";
            } else {
                $text .= '<b>Сумма покупки: </b>' . $request->sum . "\n";
                $text .= '<b>Список нужных товаров: </b>' . $request->products . "\n" . "\n";
            }
            if ($request->mode == RequestModeService::RUNNING || $request->mode == RequestModeService::DONE) {
                if ($request->mode == RequestModeService::DONE) {
                    $text .= 'Выполнил ';
                } else {
                    $text .= 'На выполнение принял ';
                }
                $text .= '<a href="tg://user?id=' . $request->user->chat_id . '">' . $request->user->user_name . '</a>';
            }

            $this->tg->sendMessage($text);
        }
    }
}