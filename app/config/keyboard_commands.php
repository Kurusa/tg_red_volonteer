<?php
return [
    \App\Services\UserKeyboardService::CREATE_REQUEST => \App\Commands\CreateRequest\RequestAddress::class,
    \App\Services\UserKeyboardService::CANCEL => \App\Commands\Start::class,
    \App\Services\UserKeyboardService::ACTIVE_REQUESTS => \App\Commands\Provider\Requests::class,
    \App\Services\UserKeyboardService::REQUESTS => \App\Commands\Operator\Requests::class,
    \App\Services\UserKeyboardService::ALL_REQUESTS => \App\Commands\Admin\Requests::class,
    \App\Services\UserKeyboardService::SEARCH => \App\Commands\Admin\SearchButtons::class,
    'курьер' => \App\Commands\Start::class,
    'оператор' => \App\Commands\Admin\UserTypeRequest::class,
];