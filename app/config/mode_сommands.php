<?php

use App\Services\UserModeService;

return [
    UserModeService::REQUEST_ADDRESS => \App\Commands\CreateRequest\RequestAddress::class,
    UserModeService::REQUEST_CUSTOMER => \App\Commands\CreateRequest\RequestCustomer::class,
    UserModeService::REQUEST_SUM => \App\Commands\CreateRequest\RequestSum::class,
    UserModeService::REQUEST_PRODUCTS => \App\Commands\CreateRequest\RequestProducts::class,
    UserModeService::REQUEST_PHONE_NUMBER => \App\Commands\CreateRequest\RequestPhoneNumber::class,
    UserModeService::REQUEST_CONFIRM => \App\Commands\Provider\ConfirmRequest::class,
    UserModeService::REQUEST_SEARCH => \App\Commands\Admin\SearchRequest::class,
];