<?php

namespace App\Commands\CreateRequest;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class RequestCustomer extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_CUSTOMER) {

            Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->update([
                'customer' => $this->tg_parser::getMessage()
            ]);
            $this->triggerCommand(RequestSum::class);

        } else {
            $this->user->mode = UserModeService::REQUEST_CUSTOMER;
            $this->user->save();
            $this->tg->sendMessageWithKeyboard('Введите ФИО заказчика', [[UserKeyboardService::CANCEL]]);
        }
    }

}