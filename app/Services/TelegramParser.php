<?php

namespace App\Services;

class TelegramParser {

    private static $data;

    public function __construct(array $data)
    {
        self::$data = $data;
    }

    public static function getMsgId()
    {
        return self::$data['message']['message_id'];
    }

    public static function getUserName()
    {
        return strval(self::$data['message']['chat']['username']);
    }

    public static function getMessage()
    {
        return trim(strval(self::$data['message']['text']));
    }

    public static function getFileId() {
        return self::$data['message']['photo'][0]['file_id'];
    }

    public static function getChatId()
    {
        return intval(self::$data['message']['chat']['id']);
    }

    public static function getWholeCallback()
    {
        return self::$data['data'];
    }

    public static function getCallbackByKey($key)
    {
        return json_decode(self::getWholeCallback(), true)[strval($key)];
    }

    public static function getIdFromCallback()
    {
        return json_decode(self::getWholeCallback(), true)['id'];
    }
}