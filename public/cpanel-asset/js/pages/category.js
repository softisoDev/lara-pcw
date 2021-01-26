$(document).ready(function () {
    //build summernote
    $('#content').summernote({
        height: 350,
    });

    //build dropify
    $('#image').dropify();

    //init select2
    initSelect2();

});

// when user out of focus on brand name input
function outFocusCategory(el) {
    document.getElementById('slug').value = slugify($(el).val());
    document.getElementById('image-title').value = $(el).val();
}



















