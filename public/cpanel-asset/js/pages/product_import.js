$(document).ready(function () {
    initSwitchery();

    initSelect2();

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        let pageNo = $(this).attr('href').split('page=')[1];
        fetchData(pageNo);
    });

    $('#mdate').bootstrapMaterialDatePicker({weekStart: 0, time: false, clearButton: true});

    initTagsInput();

    initRemoteSelect2();
});

function initPaginator() {
    $('#luckmoshy').luckmoshyPagination({

        // the total number of pages
        totalPages: $('.luckmoshy-paginator').length,

        // the current page to show on start
        startPage: 1,

        // maximum visible pages
        visiblePages: 10,

        // callback function
        onPageClick: function (event, page) {
            $('.page-active').removeClass('page-active');
            $('#container-pagnation' + page).addClass('page-active');
        }
    });

}


$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

// initialize remote select2 for product category
function initRemoteSelect2() {
    $('.allCategories').each(function () {
        $(this).select2({
            ajax: {
                url: addSlash2Url(route('admin.category.select2')),
                type: "post",
                dataType: "json",
                delay: 500,
                minimumInputLength: 3,
                data: function (params) {
                    return {
                        "_token": CSRF_TOKEN,
                        "term": params.term,
                    };
                }
            }
        });
    })
}

// fetch search result from file
function fetchDataByFile() {
    let filename = document.getElementById('fileName').value;
    if (filename === "") {
        runSweetAlert('Please select file', '', 'warning');
        return false;
    }
    document.getElementById('tempDir').value = 0;
    document.getElementById('searchName').value = filename;
    showElement('processing-area');
    fetchData();
}

// fetch data from file
function fetchData(page = 1) {
    let dataLength = document.getElementById('dataLength').value;
    let searchName = document.getElementById('searchName').value;
    let tempDir = document.getElementById('tempDir').value;

    $.ajax({
        url: addSlash2Url(route('admin.product.import.make')),
        type: "POST",
        dataType: "JSON",
        data: {
            "_token": CSRF_TOKEN,
            "page": page,
            "dataLength": dataLength,
            "searchName": searchName,
            "tempDir": tempDir,
        },
        success: function (response) {
            $('#result-area').html(response.result);
            $('#parsed-result').html(response.parsedData);
            initRemoteSelect2();
            initSwitchery();
            initTagsInput();
        },
        error: function (response) {
            sweet_error();
        }
    });
}

/* import result form action*/
$('#result-form').on('submit', function (event) {
    event.preventDefault();
    let form = document.getElementById('result-form');

    $.ajax({
        url: addSlash2Url(route('admin.product.import.save')),
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        dataType: "JSON",
        data: new FormData(form),
        success: function (response) {
            if (response.success) {
                runSweetAlert(response.message, '', 'success');
                fetchData();
            } else if (!response.success || typeof (response.errors) !== "undefined") {
                $('#error-area').empty().html(response.errors);
            } else {
                runSweetAlert(response.message, '', 'error');
            }
        },
        error: function (response) {
            sweet_error();
        }
    });
});

/* search from api */
$('#search-form').on('submit', function (event) {
    event.preventDefault();
    let form = document.getElementById('search-form');
    $.ajax({
        url: addSlash2Url(route('admin.product.import.search')),
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        dataType: "JSON",
        data: new FormData(form),
        success: function (response) {
            if (response.success) {
                $('#searchName').val(response.search_file);
                document.getElementById('tempDir').value = 1;
                showElement('processing-area');
                fetchData();
            } else {
                $('#error-area').empty().html(response.errors);
            }
        },
        error: function (response) {
            sweet_error();
        }
    });
});

/* set primary image of product */
function setAsPrimaryImage(el, event) {
    event.stopPropagation();
    let imgSrc = $(el).prev().prop('src');
    $(".modal.show input[name^=products]").val(imgSrc);
    runSweetAlert('Image is set successfully', '', 'success');
}

/* save search result as json file */
function saveSearch() {
    let tempFileName = document.getElementById('searchName').value;
    let fileName = document.getElementById('newFileName').value;
    if (fileName === "") {
        runSweetAlert("File name can't be empty", '', 'error');
        return false;
    }
    $.ajax({
        url: addSlash2Url(route('admin.product.import.search.save')),
        type: "POST",
        dataType: "JSON",
        data: {"tempFileName": tempFileName, "fileName": fileName, "_token": CSRF_TOKEN},
        success: function (response) {
            if (response.success === true) {
                $('#saveSearchModal').modal('hide');
                runSweetAlert(response.message, '', 'success');
            } else {
                runSweetAlert(response.message, '', 'error');
            }
        },
        error: function (response) {
            sweet_error();
        }
    });
}


function initImagePickerOnModal(el) {
    let selectorId = $(el).data('imgpicker-id');
    $('#' + selectorId).imagepicker({
        show_label: true,
        isForProductImport: true,
    });
}


