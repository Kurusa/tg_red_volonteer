<?php

namespace App\Commands\Admin;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Models\RequestConfirm;
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
            $text .= '<b>Номер телефона: </b>' . $request->phone_number . "\n";
            $text .= '<b>Сумма покупки: </b>' . $request->sum . "\n";
            $text .= '<b>Список нужных товаров: </b>' . $request->products . "\n" . "\n";

            if ($request->mode == RequestModeService::RUNNING || $request->mode == RequestModeService::DONE) {
                if ($request->mode == RequestModeService::DONE) {
                    $checks = RequestConfirm::where('request_id', $request->id)->get();
                    foreach ($checks as $check) {
                        $this->tg->sendPhoto($check->file_id);
                    }
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