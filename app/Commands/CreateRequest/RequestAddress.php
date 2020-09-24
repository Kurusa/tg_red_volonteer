<?php

namespace App\Commands\CreateRequest;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class RequestAddress extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_ADDRESS) {

            if (strlen($this->tg_parser::getMessage()) >= 5) {
                Request::create([
                    'operator_id' => $this->user->id,
                    'address' => $this->tg_parser::getMessage(),
                ]);
                $this->triggerCommand(RequestCategory::class);
            } else {
                $this->tg->sendMessage('Текст слишком короткий');
            }

        } else {
            $this->user->mode = UserModeService::REQUEST_ADDRESS;
            $this->user->save();

            $this->tg->sendMessage('💁🏻‍♂️Приветствуем тебя, дорогой Доброволец отдела Call центра. В этом меню, ты сможешь создать заявку для доставки необходимого и передать её нашим волонтёрам!☎️

‼️Пожалуйста, будь внимателен при заполнении информации!‼️');
            $this->tg->sendMessageWithKeyboard('Введите адресс доставки', [[UserKeyboardService::CANCEL]]);
        }
    }

}