<?php

namespace App\Commands\Admin;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\TelegramKeyboard;

class Requests extends BaseCommand {

    function processCommand($par = false)
    {
        $requests = $par ?: Request::where('mode', '!=', RequestModeService::FILLING)->get();
        foreach ($requests as $request) {
            $button_text = '✅ ';
            if ($request->mode == RequestModeService::OPEN || $request->mode == RequestModeService::RUNNING) {
                $button_text = '🔵 ';
            }
            $button_text .= $request->address;

            TelegramKeyboard::addButton($button_text, [
                'a' => 'admin_request',
                'id' => $request->id
            ]);
        }

        $this->tg->sendMessageWithInlineKeyboard('заявки', TelegramKeyboard::get());
    }

}