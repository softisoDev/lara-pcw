<?php

return [
    'amazon' => [
        'patterns'        => [
            [
                'pattern' => '/<span id=\"priceblock_saleprice\" \b[^>]*>(.*?)<\/span>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<span id=\"priceblock_ourprice\" \b[^>]*>(.*?)<\/span>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<span id=\"price_inside_buybox\" \b[^>]*>(.*?)<\/span>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/(<span id="color_name_0_price" \b[^>]*>)\W*(.*?)\W*(\W* <\/span>)/',
                'index'   => 0,
            ],
            [
                'pattern' => '/(<span id="style_name_0_price" \b[^>]*>)\W*(.*?)\W*(\W* <\/span>)/',
                'index'   => 0,
            ],
            [
                'pattern' => '/(<span id="size_name_0_price" \b[^>]*>)\W*(.*?)\W*(\W* <\/span>)/',
                'index'   => 0,
            ],
            [
                'pattern' => '/(<div id="buyNew_noncbb">)\W*(.*?)\W*(<\/div>)/',
                'index'   => 2,
            ],
            [
                'pattern' => '/(<a \b[^>]* id="a-autoid-\d-announce">)\W*(.*?)\W*(<span class="a-size-base a-color-price a-color-price">)\W*(.*?)\W*(\W*<\/span>)/s',
                'index'   => 0,
            ],
            [
                'pattern' => '/(<div id="buybox" \b[^>]*>)\W*(.*?)\W*(<span \b[^>]*>)\W*(.*?)\W*(\W*<\/span>)/s',
                'index'   => 0,
            ],
            [
                'pattern' => '/(<span id="unqualified-buybox-olp">)(.*?)(<\/span>)/s',
                'index'   => 2,
            ],
            [
                'pattern' => '/(<div id="olp-new" \b[^>]*>)\W*(.*?)\W*(<\/div>)/s',
                'index'   => 2,
            ],
        ],
        'content_element' => '',
    ],

    'ebay' => [
        'patterns' => [
            [
                'pattern' => '/<span id="convbidPrice" \b[^>]*>(.*?)<\/span>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<div class="original-price">(.*?)<\/div>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<span \b[^>]* id=\"prcIsum\" \b[^>]*>(.*?)<\/span>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<span class="item-price\b[^>]*">(.*?)<\/span>|<span class="item-price\b[^>]*"\b[^>]*>(.*?)<\/span>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<h\d class="display-price">(.*?)<\/h\d>/',
                'index'   => 1,
            ],
            [
                'pattern' => '/<span class=\"cc-main-price\">(.*?)<\/span>/',
                'index'   => 0,
            ],
            [
                'pattern' => '/<span class=\"vi-bin-primary-price__main-price\">(.*?)<\/span>/',
                'index'   => 1,
            ],
        ]
    ],

    'walmart' => [
        'patterns' => [
            [
                'pattern' => '/<span\b[^>]* id=\"price\">(.*?)<\/span>/',
                'index'   => 1,
            ],
        ]
    ],

    'bhphotovideo' => [
        'patterns' => [
            [
                'pattern' => '/<div class=\"price_1DPoToKrLP8uWvruGqgtaY\" data-selenium=\"pricingPrice\">(.*?)<\/div>/',
                'index'   => 1,
            ],
        ]
    ],

    'newegg'  => [
        'patterns' => [
            [
                'pattern' => '/(\W*\"@type\":\"Product\"\W*)(.*?)(\W*\b[^>]*\W*)/',
                'index'   => 0,
            ],
        ],
    ],
    'bestbuy' => [
        'patterns' => [
            [
                'pattern' => '/<div class=\"priceView-hero-price \b[^>]*\">(.*?)<\/div>/',
                'index'   => 1,
            ]
        ]
    ]
];
