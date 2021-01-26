$(document).ready(function () {
    //init select2
    initSelect2();
    //init summerNote
    initSummerNote();

    initTagsInput();

    //initDataTable();

});


function addFeatureField() {
    let field = '<div class="form-group m-0 row"><div class="col-11"><input class="form-control" name="features[]" type="text"></div><div class="col-1 pt-2"><i class="fa fa-trash text-red cursor-pointer" onclick="removeRow(this, false)"></i></div> </div>';

    $('#feature-fields').append(field);
}

function initDataTable() {
    let product = $('#product_id').val();

    let drawTable = $('#main-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            url: addSlash2Url(route('admin.product.variation.list', {product: product})),
            dataType: "JSON",
            type: "POST",
            data: {"_token": CSRF_TOKEN},
        },
        dom: "<'row'<'#custom-filter.col-md-12'l>>" +
            "<'row'<'#toolbar_buttons.col-md-8'B><'col-md-4'f>>" +
            "rtip",
        initComplete: function () {
            //do something
        },
        "order": [[2, "asc"]],
        columnDefs: [
            {
                "targets": [0, 5, 6, 7],
                visible: false,
                searchable: false,
            },
        ],
        "pageLength": 25,
        "lengthMenu": [10, 15, 25, 50, 100],
        "scrollX": true,
        "fnDrawCallback": function () {
            drawTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                let singleRow = this.node();
                let dataID = drawTable.row(singleRow).data()['id'];
                $(singleRow).attr('data-id', dataID);
            });
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'source', name: 'source'},
            {data: 'color', name: 'color'},
            {data: 'current_price', name: 'current_price'},
            {data: 'price_max', name: 'price_max'},
            {data: 'price_min', name: 'price_min'},
            {data: 'availability', name: 'availability'},
            {data: 'size', name: 'size'},
            {data: 'merchant', name: 'merchant'},
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
                        text: '<i class="fa fa-eye"></i> Show',
                        className: ".dt-more-about-item",
                        action: function (e, dt, node, config) {
                            let get_row = drawTable.rows({selected: true}).nodes();
                            let dataID = $(get_row[0]).data('id');
                            //window.location.href = addSlash2Url(route('admin.product.show', {id: dataID}));
                        }
                    },
                    {
                        text: '<i class="fa fa-pencil"></i> Edit',
                        className: ".dt-edit-item",
                        action: function (e, dt, node, config) {
                            let get_row = drawTable.rows({selected: true}).nodes();
                            let dataID = get_row[0].dataset.id;
                            //window.location.href = addSlash2Url(route('admin.product.edit', {id: dataID}));
                        }
                    },
                    {
                        text: '<i class="fa fa-image"></i> Upload Media',
                        className: ".dt-edit-item",
                        action: function (e, dt, node, config) {
                            let get_row = drawTable.rows({selected: true}).nodes();
                            let dataID = get_row[0].dataset.id;
                            //window.location.href = addSlash2Url(route('admin.product.images', {product: dataID}));
                        }
                    },
                    {
                        text: '<i class="fa fa-trash"></i> Delete',
                        className: ".dt-delete-item",
                        action: function (e, dt, node, config) {
                            let get_row = drawTable.rows({selected: true}).nodes();
                            let dataID = $(get_row[0]).data('id');
                            //removeData(addSlash2Url(route('admin.product.destroy', {id: dataID}), dataID));
                        }
                    }
                ],
            },
        ],
        select: true
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

