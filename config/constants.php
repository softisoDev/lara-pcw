<?php

return [
    'media_type' => [
        'image',
        'video',
        'file',
        'music',
    ],

    'media_mimes' => [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
    ],

    'image' => [
        'no_image' => 'https://larapcw.com/uploads/image/no-image-available.jpg',
    ],

    'currency' => [
        'USD'  => '$',
        'US'   => '$',
        'GBP'  => '£',
        'EURO' => '€',
        'EUR'  => '€',
        'CAD'  => 'CAD',
        'AUD'  => 'AUD',
        'INR'  => 'INR',
        'JPY'  => '¥',
        'CHF'  => '₣',
        'KWD'  => 'KWD',
        'BHD'  => 'BHD',
        'OMR'  => 'OMR',
        'JOD'  => 'JOD',
        'KYD'  => 'KYD',
        'SGD'  => 'SGD',
    ],

    'selectBox' => [
        'trueFalse'  => [
            0 => 'false',
            1 => 'true',
        ],
        'dataLength' => [
            10 => 10,
            20 => 20,
            50 => 50,
        ]
    ],

    'dataTableAjaxError' => [

        'draw'            => 0,
        'recordsTotal'    => 0,
        'recordsFiltered' => 0,
        'data'            => [],
        'error'           => 'Request is not ajax!',

    ],

    'alerts' => [
        'add' => [
            'success' => [
                'title'    => 'Data added successfully',
                'type'     => 'success',
                'position' => 'center',
            ],

            'fail' => [
                'title'    => "Item can't be added",
                'type'     => 'error',
                'position' => 'center',
            ]
        ],

        'update' => [
            'success' => [
                'title'    => 'Data updated successfully',
                'type'     => 'success',
                'position' => 'center',
            ],
            'fail'    => [
                'title'    => "Something went wrong while updating data",
                'type'     => 'error',
                'position' => 'center',
            ]
        ],

        'delete' => [
            'success' => [
                'title'    => 'Data deleted successfully',
                'type'     => 'success',
                'position' => 'center',
            ],
            'fail'    => [
                'title'    => "Something went wrong while deleting data",
                'type'     => 'error',
                'position' => 'center',
            ]
        ],
        'run'    => [
            'success' => [
                'title'    => 'Process run successfully',
                'type'     => 'success',
                'position' => 'center',
            ]
        ]
    ],

    'responses' => [
        'ajax' => [
            'fail' => [
                'success' => false,
                'message' => 'Oops! Something went wrong'
            ],
        ]
    ],
    'header'    => [
        'default_description' => 'larapcw.com',
        'default_page_title'  => 'larapcw.com'
    ],

    'queue' => [
        'cache_refresher' => 'cache-refresher',
    ],
];
