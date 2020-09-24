<?php

namespace App\Commands;

use App\Models\Request;
use App\Services\RequestModeService;
use App\Services\UserKeyboardService;
use App\Services\UserModeService;
use CURLFile;

class Start extends BaseCommand {

    function processCommand($par = false)
    {
            $text = 'Здравствуй Друг!👋🏼

🔅Тебя приветствует Бот Поддержки доброволецев на дом, Российского Красного Креста‼️

❗️Бот разработан для успешной  координации волонтёров и добровольцев программы, по борьбе с рампромтронением COVID-19❗️

Мы оказываем помощь пожилым людям, оказавшихся в ситуации ЖЕСТКОЙ самоизоляции.🏡
Бот абсолютно бесплатен и не преследует какой-либо коммерческой выгоды.🙅🏼‍♂️


По всем техническим вопросам обращаться к администратору👨🏼‍💻 @horanq';

        if ($this->tg_parser::getMessage() == 'доставщик' || $this->user->mode !== UserModeService::WAITING) {
            $this->user->mode = UserModeService::DONE;
            $this->user->request_id = 0;
            $this->user->save();

            Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->delete();

            $keyboard = UserKeyboardService::KEYBOARD_DATA[$this->user->type];
            $this->tg->sendPhotoCaption(new CURLFile($_SERVER['DOCUMENT_ROOT'] . '/app/start.jpg'), $par ?: $text);
            $this->tg->sendMessageWithKeyboard('главное меню', [$keyboard]);
        } else {
            if ($this->user->mode == UserModeService::WAITING) {
                if ($this->tg_parser::getMessage() == 'курьер') {
                    $this->user->mode = UserModeService::DONE;
                    $this->user->request_id = 0;
                    $this->user->save();

                    Request::where('operator_id', $this->user->id)->where('mode', RequestModeService::FILLING)->delete();

                    $keyboard = UserKeyboardService::KEYBOARD_DATA[$this->user->type];
                    $this->tg->sendPhotoCaption(new CURLFile($_SERVER['DOCUMENT_ROOT'] . '/app/start.jpg'), $par ?: $text, [$keyboard]);
                    exit;
                }
                $this->tg->sendPhotoCaption(new CURLFile($_SERVER['DOCUMENT_ROOT'] . '/app/start.jpg'), 'Здравствуй Друг!👋🏼

🔅Тебя приветствует Бот Поддержки доброволецев на дом, Российского Красного Креста‼️

❗️Бот разработан для успешной  координации волонтёров и добровольцев программы, по борьбе с рампромтронением COVID-19❗️

Мы оказываем помощь пожилым людям, оказавшихся в ситуации ЖЕСТКОЙ самоизоляции.🏡
Бот абсолютно бесплатен и не преследует какой-либо коммерческой выгоды.🙅🏼‍♂️


По всем техническим вопросам обращаться к администратору👨🏼‍💻 @horanq');
                $this->tg->sendMessageWithKeyboard('Пожалуйста, перед началом работы выбери категорию добровольца

⛑А) Если ты Доброволец - Курьер, выбери соответствующую группу и можешь приступать к работе

⛑Б) Если ты Доброволец - Оператор Call центра, выбери группу и дождись одобрения заявки администратором', [
                    ['курьер'], ['оператор']
                ]);
                exit;
            }
        }
    }

}