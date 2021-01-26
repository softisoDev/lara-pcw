$(document).ready(function () {
    $('#nestable2').nestable();
});

function updateTree() {
    let nestData = $('#nestable2').nestable('serialize');

    $.ajax({
        url: addSlash2Url(route('admin.category.order.update')),
        type: "POST",
        dataType: "JSON",
        data:{"_token":CSRF_TOKEN, "data":nestData},
        success:function (response) {
            runSweetAlert(response.message, '', 'success');
        },
        error:function (response) {
            sweet_error();
        }
    });
}
