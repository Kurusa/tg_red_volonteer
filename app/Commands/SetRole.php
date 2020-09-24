<?php

namespace App\Commands;

use App\Models\User;

class SetRole extends BaseCommand {

    function processCommand($par = false)
    {
        if ($this->user->type == 'ADMIN') {
            $types = ['ADMIN', 'OPERATOR', 'PROVIDER'];
            $explodes = explode(' ', $this->tg_parser::getMessage());
            if (User::where('chat_id', $explodes[1])->count() && in_array(strtoupper($explodes[2]), $types)) {
                User::where('chat_id', $explodes[1])->update([
                    'type' => $explodes[2]
                ]);
            }
            $this->tg->sendMessage('Готово');
        }
    }

}