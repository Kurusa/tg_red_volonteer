<?php

namespace App\Services;

class TelegramApi {

    public $result;
    public $chat_id;
    public $curl;

    public $API = 'https://api.telegram.org/bot';

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function api($method, $params, $header = false)
    {
        $url = $this->API . env('TELEGRAM_BOT_TOKEN') . '/' . $method;
        $url .= $header ? '?chat_id=' . $this->chat_id : '';
        return $this->do($url, $params, $header);
    }

    private function do(string $url, array $params = [], $header = false): ?array
    {
        $params = $header ? $params : json_encode($params);
        $content_type = $header ?: 'application/json';

        //curl_setopt($this->curl, CURLOPT_STDERR, true);
        //curl_setopt($this->curl, CURLOPT_VERBOSE, true);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-Type: ' . $content_type,
        ]);

        $exec = curl_exec($this->curl);
        $this->result = json_decode($exec, TRUE);
        error_log($exec);
        return $this->result;
    }

    public function isTyping()
    {
        $this->api('sendChatAction', ['chat_id' => $this->chat_id, 'action' => 'typing']);
    }

    public function sendMediaGroup(array $media_group, $chat_id = null)
    {
        $mediaResult = [];
        foreach ($media_group as $media) {
            $mediaResult[] = [
                'type' => 'photo',
                'media' => $media,
            ];
        }

        self::api('sendMediaGroup', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'media' => json_encode($mediaResult)
        ]);
    }

    public function sendMessage(string $text, $chat_id = null, $markdown = false)
    {
        $this->isTyping();
        $this->api('sendMessage', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'text' => $text,
            'parse_mode' => $markdown ? 'markdown' : 'HTML',
        ]);
    }

    public function sendPhoto(string $photo, $chat_id = null)
    {
        $this->isTyping();
        $this->api('sendPhoto', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'photo' => $photo,
        ]);
    }

    public function sendPhotoCaption($photo, $caption = null, $encoded_markup = null, $chat_id = null)
    {
        $this->api('sendPhoto', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'photo' => $photo,
            'caption' => $caption ?: '',
            'parse_mode' => 'HTML',
            /*'reply_markup' => [
                'keyboard' => $encoded_markup,
                'one_time_keyboard' => false,
                'resize_keyboard' => true,
            ],*/
        ], 'multipart/form-data');
    }

    public function removeKeyboard(string $text, $chat_id = null)
    {
        $this->api('sendMessage', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'text' => $text,
            'reply_markup' => [
                'remove_keyboard' => true,
            ],
            'parse_mode' => 'Markdown',
        ]);
    }

    public function sendContact(string $phone_number, string $first_name, $chat_id = null)
    {
        $this->isTyping();
        $this->api('sendContact', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'phone_number' => $phone_number,
            'first_name' => $first_name
        ]);
    }

    public function sendMessageWithKeyboard(string $text, array $encodedMarkup, $chat_id = null, $parse_mode = 'HTML')
    {
        $this->isTyping();
        $this->api('sendMessage', [
            'chat_id' => $chat_id ?: $this->chat_id, 'text' => $text,
            'reply_markup' => [
                'keyboard' => $encodedMarkup,
                'one_time_keyboard' => false,
                'resize_keyboard' => true,
            ], 'parse_mode' => $parse_mode,
        ]);
    }

    public function sendMessageWithInlineKeyboard(string $text, $buttons, $chat_id = null)
    {
        $this->isTyping();
        $this->api('sendMessage', [
            'chat_id' => $chat_id ?: $this->chat_id,
            'reply_markup' => [
                'inline_keyboard' => $buttons,
            ],
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public function answerCallbackQuery($callbackQueryId)
    {
        $this->api('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
        ]);
    }

    public function deleteMessage(int $messageId)
    {
        $this->api('deleteMessage', [
            'chat_id' => $this->chat_id, 'message_id' => $messageId,
        ]);
    }

    public function updateMessageKeyboard(int $messageId, string $newText, array $newButton, $chat_id = null)
    {
        $this->api('editMessageText', [
            'chat_id' => $chat_id ?: $this->chat_id, 'message_id' => $messageId, 'text' => $newText, 'reply_markup' => [
                'inline_keyboard' => $newButton,
            ], 'parse_mode' => 'HTML',
        ]);
    }

    public function getFile($file_id)
    {
        return self::api('getFile', ['file_id' => $file_id]);
    }

    public function sendLocation($lat, $lon, $buttons = null, $chat_id = null)
    {
        $this->api('sendlocation', [
            'chat_id' => $this->chat_id,
            'longitude' => $lon,
            'latitude' => $lat,
            'reply_markup' => [
                'inline_keyboard' => $buttons
            ],
        ]);
    }

    public function __destruct()
    {
        $this->curl = curl_close($this->curl);
    }

}