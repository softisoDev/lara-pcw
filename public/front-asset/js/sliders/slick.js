function initSlickSlider() {
    $('.slider-items-slick').slick({
        infinite: true,
        swipeToSlide: true,
        slidesToShow: 4,
        dots: true,
        slidesToScroll: 2,
        prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1
                }
            }
        ],
        autoplay: true,
        autoplaySpeed: 2000,
    });
}
