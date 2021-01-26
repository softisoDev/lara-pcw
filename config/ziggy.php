<?php

/*return [
    // 'whitelist' => ['home', 'api.*'],
    'blacklist' => ['debugbar.*', 'cpanel.*', 'admin.*'],
];*/

return [
    'groups' => [
        'front' => [
            'front.*',
        ],
        'cpanel' => [
            'cpanel.*',
            'admin.*',
        ],
    ],
];
