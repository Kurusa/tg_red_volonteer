<?php
return [
    'request_category' => \App\Commands\CreateRequest\RequestCategory::class,
    'cancel_request' => \App\Commands\Provider\CancelRequest::class,
    'take_request' => \App\Commands\Provider\TakeRequest::class,
    'confirm_request' => \App\Commands\Provider\ConfirmRequest::class,

    'provider_request' => \App\Commands\Provider\RequestInfo::class,
    'operator_request' => \App\Commands\Operator\RequestInfo::class,
    'admin_request' => \App\Commands\Admin\RequestInfo::class,
    'search' => \App\Commands\Admin\SearchRequest::class,
    'type_approve' => \App\Commands\Admin\UserTypeRequest::class,
    'type_decline' => \App\Commands\Admin\UserTypeRequest::class,
];