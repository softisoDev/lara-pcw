const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.styles([
        'public/front-asset/css/bootstrap.css',
        'public/front-asset/plugins/slickslider/slick.css',
        'public/front-asset/plugins/owlcarousel/assets/owl.carousel.css',
        'public/front-asset/plugins/owlcarousel/assets/owl.theme.default.css',
        'public/front-asset/css/ui.css',
        'public/front-asset/css/responsive.css',
        'public/front-asset/plugins/star-rating/css/star-rating-svg.css',
        'public/plugins/photoswipe/photoswipe.css',
        'public/plugins/photoswipe/default-skin/default-skin.css',
        'public/front-asset/fonts/fontawesome/css/all.min.css',
    ],
    'public/css/all.css').minify('public/css/all.css');

mix.scripts([
        'resources/assets/js/front_ziggy.min.js',
        'public/front-asset/js/jquery-2.0.0.min.js',
        'public/front-asset/js/bootstrap.bundle.min.js',
        'public/front-asset/js/script.js',
        'public/front-asset/plugins/slickslider/slick.min.js',
        'public/front-asset/plugins/owlcarousel/owl.carousel.min.js',
        'public/front-asset/js/sliders/slick.js',
        'public/front-asset/plugins/star-rating/jquery.star-rating-svg.js',
        'public/plugins/photoswipe/photoswipe.min.js',
        'public/plugins/photoswipe/photoswipe-ui-default.min.js',
        'public/front-asset/js/custom.js',
        'public/front-asset/js/pages/products.js',
        'public/front-asset/js/pages/search.js',
    ],
    'public/js/all.js').minify('public/js/all.js');

