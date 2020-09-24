<?php

namespace App\Commands;

use App\Models\User;
use App\Services\TelegramParser;
use App\Services\TelegramApi;
use App\Services\UserModeService;

abstract class BaseCommand {

    /**
     * @var TelegramParser
     */
    protected $tg_parser;

    /**
     * @var TelegramApi
     */
    protected $tg;

    protected $user;
    private $update;

    function handle(array $update, $par = false)
    {
        $this->update = $update;
        $this->tg_parser = new TelegramParser($update);
        $this->tg = new TelegramApi();
        $this->tg->chat_id = $this->tg_parser::getChatId();

        $this->user = User::where('chat_id', $this->tg_parser::getChatId())->first();
        if (!$this->user) {
            $this->user = User::create([
                'chat_id' => $this->tg_parser::getChatId(),
                'user_name' => $this->tg_parser::getUserName(),
                'mode' => UserModeService::WAITING
            ]);
        }

        $this->processCommand($par);
    }

    function triggerCommand($class, $par = false)
    {
        (new $class())->handle($this->update, $par);
    }

    abstract function processCommand($par = false);

}