$(document).ready(function () {
    //build summernote
    $('#content').summernote({
        height: 350,
    });
});

function outFocusKeyword(el) {
    document.getElementById('slug').value = slugify($(el).val());
}
