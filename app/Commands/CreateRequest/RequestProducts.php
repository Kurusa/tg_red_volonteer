<?php

namespace App\Commands\CreateRequest;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class RequestProducts extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_PRODUCTS) {
            if (strlen($this->tg_parser::getMessage()) > 5) {
                Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->update([
                    'products' => $this->tg_parser::getMessage(),
                ]);
                $this->triggerCommand(RequestPhoneNumber::class);
            } else {
                $this->tg->sendMessage('Текст слишком короткий');
            }
        } else {
            $this->user->mode = UserModeService::REQUEST_PRODUCTS;
            $this->user->save();
            $this->tg->sendMessageWithKeyboard('Напишите список продуктов', [[UserKeyboardService::CANCEL]]);
        }
    }

}