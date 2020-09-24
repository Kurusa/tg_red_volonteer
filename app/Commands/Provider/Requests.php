<?php

namespace App\Commands\Provider;

use App\Commands\BaseCommand;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\TelegramKeyboard;

class Requests extends BaseCommand {

    function processCommand($par = false)
    {
        $requests = Request::where('mode', RequestModeService::OPEN)->orWhere('mode', RequestModeService::RUNNING)->get();
        if ($requests->count()) {
            foreach ($requests as $request) {
                $button_text = $request->mode == RequestModeService::OPEN ? '🔵 ' : '🔴 ';
                if ($request->provider_id == $this->user->id) {
                    $button_text = '🏃 ';
                }
                $button_text .= $request->address;

                $available = ($request->mode == RequestModeService::OPEN || $request->provider_id == $this->user->id);
                TelegramKeyboard::addButton($button_text, [
                    'a' => 'provider_request',
                    'available' => $available,
                    'id' => $request->id
                ]);
            }

            $this->tg->sendMessageWithInlineKeyboard('✍🏼В этом меню размещены все активные заявки по доставке продуктов и медикаментов, имеющихся на данный момент.

🚨Знаком 🔵  - отмечены все свободные заявки. 

🚨Знаком 🔴 - отмечены заявки, взятые на исполнение другими волонтёрами.
‼️Будьте внимательны, они могут снова быть свободны!‼️


✅Выберите свободную заявку и приступайте к действиям!', TelegramKeyboard::get());
        } else {
            $this->tg->sendMessage('Пока что нет доступных заявок');
        }
    }

}