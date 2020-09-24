<?php

namespace App\Commands\Provider;

use App\Commands\BaseCommand;
use App\Commands\Start;
use App\Models\Request;
use App\Models\RequestConfirm;
use App\Services\RequestModeService;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class ConfirmRequest extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_CONFIRM) {

            RequestConfirm::create([
                'request_id' => $this->user->request_id,
                'file_id' => $this->tg_parser::getFileId()
            ]);

            Request::find($this->user->request_id)->update([
                'mode' => RequestModeService::DONE
            ]);

            $this->user->request_id = 0;
            $this->user->save();

            $this->triggerCommand(Start::class, '✅Готово! Ты супер!😀 Ещё одному человеку была оказана помощь.👍🏼 И это сделал ТЫ!🔥');
        } else {
            $this->user->mode = UserModeService::REQUEST_CONFIRM;
            $this->user->request_id = $this->tg_parser::getCallbackByKey('id');
            $this->user->save();

            $this->tg->sendMessageWithKeyboard('📌Дело почти сделано! Осталось приложить чеки.🧾', [[UserKeyboardService::CANCEL]]);
        }
    }
}