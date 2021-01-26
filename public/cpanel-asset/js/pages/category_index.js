$(document).ready(function () {
    initSelect2();
    if ($.fn.dataTable.isDataTable( '#main-table' )){
        const  table = $('#main-table').DataTable();

        table.button().add(2, {
            extend: "collection",
            text: '<i class="la la-cog"></i> Actions',
            className: "actions",
            enabled: false,
            buttons: [
                {
                    text: '<i class="fa fa-eye"></i> Show',
                    className: ".dt-more-about-item",
                    action: function (e, dt, node, config) {
                        let get_row = table.rows({selected: true}).nodes();
                        let dataID = $(get_row[0]).data('id');
                        window.location.href = addSlash2Url(route('admin.category.show', {id: dataID}));
                    }
                },
                {
                    text: '<i class="fa fa-pencil"></i> Edit',
                    className: ".dt-edit-item",
                    action: function (e, dt, node, config) {
                        let get_row = table.rows({selected: true}).nodes();
                        let dataID = get_row[0].dataset.id;
                        window.location.href = addSlash2Url(route('admin.category.edit', {id: dataID}));
                    }
                },
                {
                    text: '<i class="fa fa-trash"></i> Delete',
                    className: ".dt-delete-item",
                    action: function (e, dt, node, config) {
                        let get_row = table.rows({selected: true}).nodes();
                        let dataID = $(get_row[0]).data('id');
                        removeData(addSlash2Url(route('admin.category.destroy', {id: dataID}), dataID));
                    }
                }
            ],
        });

        if (typeof initActionButton == 'function') {
            initActionButton(table);
        }
    }
});

function initTrashedTable() {
    let trashedTable = $('#trash-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            url: addSlash2Url(route('admin.category.datatable.trashed')),
            dataType: "JSON",
            type: "POST",
            data: {"_token": CSRF_TOKEN},
        },
        dom: "<'row'<'#custom-filter.col-md-12'l>>" +
            "<'row'<'#toolbar_buttons.col-md-8'B><'col-md-4'f>>" +
            "rtip",
        initComplete: function () {
        },
        "order": [[1, "asc"]],
        "pageLength": 25,
        "lengthMenu": [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "All"]],
        "scrollX": true,
        columnDefs: [
            {
                "targets": [0],
                visible: false,
                searchable: false
            },
        ],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'deleted_at', name: 'deleted_at'},
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
                    trashedTable.ajax.reload();
                }
            },
            {
                extend: "collection",
                text: '<i class="la la-cog"></i> Actions',
                className: "actions",
                enabled: false,
                buttons: [
                    {
                        text: '<i class="fa fa-reply"></i> Restore',
                        className: ".dt-more-about-item",
                        action: function (e, dt, node, config) {
                            let get_row = trashedTable.rows({selected: true}).nodes();
                            let dataID = $(get_row[0]).data('id');
                            restoreData(addSlash2Url(route('admin.category.restore', {id: dataID}), dataID));
                        }
                    },
                    {
                        text: '<i class="fa fa-trash"></i> Delete forever',
                        className: ".dt-delete-item",
                        action: function (e, dt, node, config) {
                            let get_row = trashedTable.rows({selected: true}).nodes();
                            let dataID = $(get_row[0]).data('id');
                            removeDataPermanently(addSlash2Url(route('admin.category.datatable.destroy.permanently', {id: dataID}), dataID));
                        }
                    }
                ],
            },
        ],
        select: true
    });

    trashedTable.on('select', function (e, dt, type, indexes) {
        let length = trashedTable.rows('.selected').data().length;
        if (length === 1) {
            trashedTable.buttons(['.actions']).enable();
        } else {
            trashedTable.buttons(['.actions']).disable();
        }
    });

    trashedTable.on('deselect', function (e, dt, type, indexes) {
        trashedTable.buttons(['.actions']).disable();
    });
}




