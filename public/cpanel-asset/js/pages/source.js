$(document).ready(function () {
    initDataTable();
});


function initDataTable() {

    let drawTable = $('#main-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            url: addSlash2Url(route('admin.product.variation.source.media.datatable')),
            dataType: "JSON",
            type: "POST",
            data: {"_token": CSRF_TOKEN},
        },
        dom: "<'row'<'col-md-6'l>>" +
            "<'row'<'#toolbar_buttons.col-md-8'B><'col-md-4'f>>" +
            "rtip",
        initComplete: function () {

        },
        "order": [[2, "asc"]],
        columnDefs: [
            {
                "targets": [0],
                visible: false,
                searchable: false
            },
            {
                "targets": [1],
                "className": "text-center",
                "width": "10px",
                searchable: false,
                render: function (row, type, val, meta) {
                    return '<img  class="img img-responsive" src="' + val.image + '">';
                }
            },
            {
                "targets": [3],
                "className": "text-center",
                "width": "10px",
                searchable: false,
                render: function (row, type, val, meta) {
                    return '<input ' + (val.status ? "checked" : "unchecked") + ' data-url="' + addSlash2Url(route('admin.product.variation.source.status')) + '" data-source="'+val.name+'" type="checkbox" class="radio-switch" data-size="mini" onchange="trueFalseSetter(this)">';
                }
            }
        ],
        "pageLength": 25,
        "lengthMenu": [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "All"]],
        "scrollX": true,
        "fnDrawCallback": function () {
            drawTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                let singleRow = this.node();
                let dataID = drawTable.row(singleRow).data()['name'];
                $(singleRow).attr('data-source', dataID);
            });

            initSwitchery();
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'image', name: 'image'},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
        ],
        buttons: [
            {
                extend: 'colvis',
                text: "Columns",
                columns: ':not(:first-child)',
            },
            {
                text: '<i class="fa fa-retweet"></i> Refresh',
                action: function (e, dt, node, config) {
                    drawTable.ajax.reload();
                }
            },
            {
                extend: "collection",
                text: '<i class="la la-cog"></i> Actions',
                className: "actions",
                enabled: false,
                buttons: [
                    {
                        text: '<i class="fa fa-image"></i> Upload Media',
                        className: ".dt-edit-item",
                        action: function (e, dt, node, config) {
                            let get_row = drawTable.rows({selected: true}).nodes();
                            let sourceName = get_row[0].dataset.source;
                            $('#source-name').val(sourceName);
                            $('#mediaUploadModal').modal('show');
                        }
                    },
                ],
            },
        ],
        select: true,
    });

    drawTable.on('select', function (e, dt, type, indexes) {
        let length = drawTable.rows('.selected').data().length;
        if (length === 1) {
            drawTable.buttons(['.actions']).enable();
        } else {
            drawTable.buttons(['.actions']).disable();
        }
    });

    drawTable.on('deselect', function (e, dt, type, indexes) {
        drawTable.buttons(['.actions']).disable();
    });
}

$('#media-upload-form').on('submit', function (event) {
    event.preventDefault();
    let form = document.getElementById('media-upload-form');

    $.ajax({
        url: addSlash2Url(route('admin.product.variation.source.media.upload')),
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


