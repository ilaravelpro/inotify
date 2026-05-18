<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/13/20, 6:58 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

return [
    'routes' => [
        'api' => [
            'status' => true,
            'notify_messages' => ['status' => true],
        ]
    ],
    'database' => [
        'migrations' => [
            'include' => true
        ],
    ],
];
?>
