<?php

namespace App;

use App\Commands\Provider\ConfirmRequest;
use App\Commands\SetRole;
use App\Models\User;
use App\Services\TelegramApi;

class WebhookController {

    public function handle()
    {
        $update = \json_decode(file_get_contents('php://input'), TRUE);
        $isCallback = !array_key_exists('message', $update);
        $response = $isCallback ? $update['callback_query'] : $update;
        $chat_id = $response['message']['chat']['id'];

        if ($isCallback) {
            $config = include(__DIR__ . '/config/callback_commands.php');
            $action = \json_decode($response['data'], true)['a'];

            if (isset($config[$action])) {
                (new $config[$action]($response))->handle($response);
            }

            $tg = new TelegramApi();
            $tg->answerCallbackQuery($response['id']);
        } else {
            // checking commands -> keyboard commands -> mode -> exit
            if (isset($update['message']['photo'])) {
                (new ConfirmRequest())->handle($update);
                return true;
            }
            if ($update['message']['text']) {
                $text = $update['message']['text'];

                if (strpos($text, '/') === 0) {
                    if (strpos($text, '/setrole') === 0) {
                        (new SetRole())->handle($update);
                        return true;
                    }
                    $handlers = include(__DIR__ . '/config/slash_commands.php');
                } else {
                    $handlers = include(__DIR__ . '/config/keyboard_commands.php');

                    if (!isset($handlers[$text])) {
                        $handlers = include(__DIR__ . '/config/mode_сommands.php');
                        $user = User::where('chat_id', $chat_id)->get();

                        if (isset($handlers[$user[0]->mode])) {
                            (new $handlers[$user[0]['mode']]($update))->handle($update);
                            return true;
                        }

                    }

                }

                if (isset($handlers[$text])) {
                    (new $handlers[$text]($update))->handle($update);
                    return true;
                }
            }
        }

        return true;
    }

}