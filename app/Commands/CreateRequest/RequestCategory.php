<?php

namespace App\Commands\CreateRequest;

use App\Commands\BaseCommand;
use App\Models\Category;
use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\TelegramKeyboard;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;

class RequestCategory extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->mode == UserModeService::REQUEST_CATEGORY) {
            if ($this->tg_parser::getCallbackByKey('a' ) == 'request_category') {
                $this->tg->deleteMessage($this->tg_parser::getMsgId());
                Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->update([
                    'category_id' => $this->tg_parser::getCallbackByKey('id')
                ]);
                $this->triggerCommand(RequestCustomer::class);
            }
        } else {
            $this->user->mode = UserModeService::REQUEST_CATEGORY;
            $this->user->save();

            foreach (Category::all() as $category) {
                TelegramKeyboard::addButton($category->title, ['a' => 'request_category', 'id' => $category->id]);
            }

            $this->tg->sendMessageWithKeyboard('Ввеберите категорию товаров', [[UserKeyboardService::CANCEL]]);
            $this->tg->sendMessageWithInlineKeyboard('список категорий', TelegramKeyboard::get());
        }
    }

}