<?php

namespace App\Commands\CreateRequest;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class RequestSum extends BaseCommand {

    function processCommand($par = false)
    {
        $request = Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->get();
        if ($this->user->mode == UserModeService::REQUEST_SUM) {
            Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->update([
                'sum' => $this->tg_parser::getMessage()
            ]);
            $this->triggerCommand($request[0]->category_id == '4' ? RequestPhoneNumber::class : RequestProducts::class);
        } else {
            $this->user->mode = UserModeService::REQUEST_SUM;
            $this->user->save();
            $this->tg->sendMessageWithKeyboard($request[0]->category_id == '4' ? 'Дополнительный комментарий' : 'Введите сумму заказа', [[UserKeyboardService::CANCEL]]);
        }
    }

}