<?php

namespace App\Commands\Provider;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;

class CancelRequest extends BaseCommand {

    function processCommand($par = false)
    {
        $this->tg->deleteMessage($this->tg_parser::getMsgId());

        Request::find($this->tg_parser::getCallbackByKey('id'))->update([
            'mode' => RequestModeService::OPEN,
            'provider_id' => null
        ]);
    }
}