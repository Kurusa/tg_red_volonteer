<?php

namespace App\Commands\CreateRequest;

use App\Commands\BaseCommand;
use App\Commands\Start;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class RequestPhoneNumber extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_PHONE_NUMBER) {
            if (strlen($this->tg_parser::getMessage()) > 5) {
                Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->update([
                    'phone_number' => $this->tg_parser::getMessage(),
                    'mode' => RequestModeService::OPEN
                ]);
                $this->triggerCommand(Start::class, '✅Поздравляю, ты МОЛОДЕЦ👌🏼! Совсем скоро, по твоей заявке отправятся наши волонтёры!😎');
            } else {
                $this->tg->sendMessage('Текст слишком короткий');
            }
        } else {
            $this->user->mode = UserModeService::REQUEST_PHONE_NUMBER;
            $this->user->save();
            $this->tg->sendMessageWithKeyboard('Укажите номер телефона заказчика', [[UserKeyboardService::CANCEL]]);
        }
    }

}