$(document).ready(function () {

    if ($.fn.dataTable.isDataTable('#main-table')) {
        const table = $('#main-table').DataTable();
        table.button().add(2, {
            extend: "collection",
            text: '<i class="la la-cog"></i> Actions',
            className: "actions",
            enabled: false,
            buttons: [
                {
                    text: '<i class="fa fa-image"></i> Upload Media',
                    className: ".dt-edit-item",
                    action: function (e, dt, node, config) {
                        let get_row = table.rows({selected: true}).nodes();
                        let sourceName = get_row[0].dataset.source;
                        $('#source-name').val(sourceName);
                        $('#mediaUploadModal').modal('show');
                    }
                },
            ],
        });
        if (typeof initActionButton == 'function') {
            initActionButton(table);
        }
    }

});


$('#media-upload-form').on('submit', function (event) {
    event.preventDefault();
    let form = document.getElementById('media-upload-form');

    $.ajax({
        url: addSlash2Url(route('admin.product.source.media.upload')),
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        dataType: "JSON",
        data: new FormData(form),
        success: function (response) {
            if (response.success) {
                form.reset();
                runSweetAlert('Success', '', 'success');
                $('#mediaUploadModal').modal('hide');
                reloadMainDataTable();
            } else {
                $('#error-area').html(response.errors);
            }
        },
        error: function (response) {
            sweet_error();
        }
    });
});


