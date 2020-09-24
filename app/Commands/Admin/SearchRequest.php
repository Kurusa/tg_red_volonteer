<?php

namespace App\Commands\Admin;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\UserModeService;

class SearchRequest extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_SEARCH) {
            $requests = Request::where($this->user->request_id, $this->tg_parser::getMessage())->get();

            if ($requests->count()) {
                $this->user->mode = UserModeService::DONE;
                $this->user->request_id = '';
                $this->user->save();
                $this->triggerCommand(Requests::class, $requests);
            } else {
                $this->tg->sendMessage('Записей не найдено');
            }

        } else {
            $this->tg->deleteMessage($this->tg_parser::getMsgId());
            $this->user->mode = UserModeService::REQUEST_SEARCH;
            $this->user->request_id = $this->tg_parser::getCallbackByKey('search');
            $this->user->save();
            $this->tg->sendMessage('Введите текст для поиска');
        }
    }
}