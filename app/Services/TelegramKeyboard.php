<?php

namespace App\Services;

class TelegramKeyboard {

    static $columns = 2;
    static $list;

    static $buttonText = 'title';

    static $action;
    static $id;
    static $add_id = null;

    static $buttons = [];

    static function build()
    {
        $one_row = [];

        foreach (self::$list as $key => $listKey) {
            $callback_data = [
                'a' => self::$action,
                'id' => self::$id ? $listKey[self::$id] : $key,
                'r_id' => self::$add_id ? $listKey[self::$add_id] : ''
            ];

            $one_row[] = [
                'text' => $listKey[self::$buttonText],
                'callback_data' => json_encode($callback_data),
            ];

            if (count($one_row) == self::$columns) {
                self::$buttons[] = $one_row;
                $one_row = [];
            }
        }
        if (count($one_row) > 0) {
            self::$buttons[] = $one_row;
        }
    }

    static function addButton(string $text, array $callback)
    {
        self::$buttons[] = [[
            'text' => $text,
            'callback_data' => json_encode($callback),
        ]];
    }

    static function addButtonUrl(string $text, string $url)
    {
        self::$buttons[] = [[
            'text' => $text,
            'url' => $url
        ]];
    }

    static function get()
    {
        return self::$buttons;
    }

}