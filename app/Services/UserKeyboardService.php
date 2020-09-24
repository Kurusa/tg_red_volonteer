<?php

namespace App\Services;

class UserKeyboardService {

    const KEYBOARD_DATA = [
        'PROVIDER' => [
            self::ACTIVE_REQUESTS
        ],

        'OPERATOR' => [
            self::CREATE_REQUEST, self::REQUESTS
        ],

        'ADMIN' => [
            self::ALL_REQUESTS, self::SEARCH
        ]
    ];

    const ACTIVE_REQUESTS = 'активные заявки';
    const REQUESTS = 'мои заявки';
    const ALL_REQUESTS = 'все заявки';
    const SEARCH = 'поиск';
    const CREATE_REQUEST = '➕ создать заявку';
    const CANCEL = '❌ отменить';

}