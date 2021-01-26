$(document).ready(function () {
    //build summernote
    $('#description').summernote({
        height: 350,
    });

    //build dropify
    $('#image').dropify();

    initSelect2();
});

// when user out of focus on brand name input
function outFocusOnBrand(el) {
    document.getElementById('slug').value = slugify($(el).val());
    document.getElementById('image-title').value = $(el).val();
}



















