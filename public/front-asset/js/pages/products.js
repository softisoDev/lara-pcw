$(document).ready(function () {
    initRateRater();
    getSimilarProducts();
});

function loadMore(el) {
    let productId = el.dataset.productId;
    let skip = $('.review-item').length;

    $.ajax({
        url: addSlash2Url(route('front.review.load.more', {productId: productId})),
        type: "POST",
        data: {"_token": CSRF_TOKEN, "skip": skip},
        success: function (response) {
            $('#review-result-area').append(response);
            if (response === "") {
                hideElement('load-more');
            }
        },
    });
}


function getSimilarProducts() {
    let product = $('#product-id').val();
    let category = $('#category-id').val();

    if ( product && category )
    {
        $.ajax({
            url: addSlash2Url(route('front.product.similar', {product: product, category: category})),
            type: "POST",
            beforeSend: function () {
                $('#loading-div').removeClass('d-none');
            },
            data: {"_token": CSRF_TOKEN},
            success: function (response) {
                $('#similar-products').empty().html(response);
                showElement('similar-product-section');
            },
            complete: function () {
                $('#loading-div').addClass('d-none');
            }
        });
    }
}
