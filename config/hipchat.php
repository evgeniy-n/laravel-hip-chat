<?php

use EvgeniyN\LaravelHipChat\ExceptionHandlers\DefaultExceptionHandler;

return [

    'default' => [
        'server'  => null,
        'api_key' => null,

        'rooms' => [
            'default' => [
                'id'      => '0',
                'api_key' => null,
                'message' => [
                    'color'  => 'red',
                    'format' => 'html',
                    'notify' => true,
                ],
            ],
        ],
    ],

    'exceptions' => [
        'default' => [
            'connection' => 'default',
            'room'       => 'default',
            'handler'    => DefaultExceptionHandler::class,
        ],
    ],
];