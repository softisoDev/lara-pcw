let table = $('#main-table').DataTable();

$(document).ready(function () {
    initSelect2();
    //init summerNote
    initSummerNote();

    table.button().add(2, {
        extend: "collection",
        text: '<i class="la la-cog"></i> Actions',
        className: "actions",
        enabled: false,
        buttons: [
            {
                text: '<i class="fa fa-trash"></i> Delete',
                className: ".dt-delete-item",
                action: function (e, dt, node, config) {
                    let get_row = table.rows({selected: true}).nodes();
                    let dataID = $(get_row[0]).data('id');
                    removeDataPermanently(route('admin.media.delete', {media: dataID}), dataID);
                }
            }
        ],
    });

    table.on('select', function (e, dt, type, indexes) {
        let length = table.rows('.selected').data().length;
        if (length === 1) {
            table.buttons(['.actions']).enable();
        } else {
            table.buttons(['.actions']).disable();
        }
    });

    table.on('deselect', function (e, dt, type, indexes) {
        table.buttons(['.actions']).disable();
    });
});


Dropzone.options.mediaUploadForm = {
    autoProcessQueue: false,
    maxFileSize: 2,
    acceptedFiles: ".jpeg,.jpg,.png",
    timeout: 5000,
    addRemoveLinks: true,
    init: function () {
        let mediaUploadDropzone = this;
        $("#upload-media-btn").on("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            const acceptedFiles = mediaUploadDropzone.getAcceptedFiles()
            for (let i = 0; i < acceptedFiles.length; i++) {
                setTimeout(function () {
                    mediaUploadDropzone.processFile(acceptedFiles[i])
                }, i * 2000)
            }
        });

        this.on("complete", function(files, response) {
            reloadMainDataTable();
        });
    }
};

