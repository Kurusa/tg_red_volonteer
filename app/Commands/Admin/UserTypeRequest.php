<?php

namespace App\Commands\Admin;

use App\Commands\BaseCommand;
use App\Models\User;
use App\Services\TelegramKeyboard;
use App\Services\UserKeyboardService;

class UserTypeRequest extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->tg_parser::getCallbackByKey('a') == 'type_approve' || $this->tg_parser::getCallbackByKey('a') == 'type_approve') {
            $this->tg->deleteMessage($this->tg_parser::getMsgId());
            \App\Models\UserTypeRequest::where([
                'chat_id' => $this->tg_parser::getCallbackByKey('id')
            ])->delete();

            if ($this->tg_parser::getCallbackByKey('a') == 'type_approve') {
                User::where([
                    'chat_id' => $this->tg_parser::getCallbackByKey('id')
                ])->update([
                    'type' => 'OPERATOR'
                ]);
                $keyboard = UserKeyboardService::KEYBOARD_DATA['OPERATOR'];
                $this->tg->sendMessageWithKeyboard('Приветствую, вашу заявку приняли ', [$keyboard], $this->tg_parser::getCallbackByKey('id'));
            }

        } else {
            \App\Models\UserTypeRequest::create([
                'chat_id' => $this->user->chat_id
            ]);
            $this->tg->removeKeyboard('Ожидайте ответа администратора');

            $admins = User::where('type', 'ADMIN')->get();
            TelegramKeyboard::addButton('✅ позволить', ['a' => 'type_approve', 'id' => $this->user->chat_id]);
            TelegramKeyboard::addButton('❌ отклонить', ['a' => 'type_decline', 'id' => $this->user->chat_id]);
            foreach ($admins as $admin) {
                $text = 'Пользователь ';
                $text .= '<a href="tg://user?id=' . $this->user->chat_id . '">' . $this->user->user_name . '</a>';
                $text .= ' хочет стать оператором';

                $this->tg->sendMessageWithInlineKeyboard($text, TelegramKeyboard::get(), $admin->chat_id);
            }
        }
    }
}