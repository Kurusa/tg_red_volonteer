<?php

namespace App\Commands\Provider;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;

class TakeRequest extends BaseCommand {

    function processCommand($par = false)
    {
        $provider_requests = Request::where('provider_id', $this->user->id)->where('mode', RequestModeService::RUNNING)->get();
        if ($provider_requests->count() >= 3) {
            $this->tg->sendMessage('Вы можете принять не более трех заявок');
        } else {
            $request = Request::find($this->tg_parser::getCallbackByKey('id'));
            $request->mode = RequestModeService::RUNNING;
            $request->provider_id = $this->user->id;
            $request->save();

            $this->tg->deleteMessage($this->tg_parser::getMsgId());
            $this->tg->sendMessage('✅Вы приняли заяву на исполнение!

‼️‼️Пожалуйста, если вы по каким-либо причинам не можете выполнить заявку в течении 3 часов с текущего момента - отмените её. Её смогут выполнить другие волонтёры‼️‼️');
            $this->triggerCommand(RequestInfo::class, $request->id);
        }
    }

}