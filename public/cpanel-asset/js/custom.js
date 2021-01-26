/*
Template Name: Material Pro Admin
Author: Themedesigner
Email: niravjoshi87@gmail.com
File: js
*/
$(function () {
    "use strict";
    $(function () {
        $(".preloader").fadeOut();
    });
    jQuery(document).on('click', '.mega-dropdown', function (e) {
        e.stopPropagation()
    });
    // ==============================================================
    // This is for the top header part and sidebar part
    // ==============================================================
    var set = function () {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        var topOffset = 70;
        if (width < 1170) {
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
            $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $(".sidebartoggler i").addClass("ti-menu");
        } else {
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
            //$(".sidebartoggler i").removeClass("ti-menu");
        }

        var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $(".page-wrapper").css("min-height", (height) + "px");
        }

    };
    $(window).ready(set);
    $(window).on("resize", set);
    // ==============================================================
    // Theme options
    // ==============================================================
    $(".sidebartoggler").on('click', function () {
        if ($("body").hasClass("mini-sidebar")) {
            $("body").trigger("resize");
            $(".scroll-sidebar, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
            //$(".sidebartoggler i").addClass("ti-menu");
        } else {
            $("body").trigger("resize");
            $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
            //$(".sidebartoggler i").removeClass("ti-menu");
        }
    });
    // topbar stickey on scroll

    $(".fix-header .topbar").stick_in_parent({});


    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").click(function () {
        $("body").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
        $(".nav-toggler i").addClass("ti-close");
    });
    $(".sidebartoggler").on('click', function () {
        //$(".sidebartoggler i").toggleClass("ti-menu");
    });
    $(".search-box a, .search-box .app-search .srh-btn").on('click', function () {
        $(".app-search").toggle(200);
    });
    // ==============================================================
    // Right sidebar options
    // ==============================================================
    $(".right-side-toggle").click(function () {
        $(".right-sidebar").slideDown(50);
        $(".right-sidebar").toggleClass("shw-rside");
    });

    $('.floating-labels .form-control').on('focus blur', function (e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    // ==============================================================
    // Auto select left navbar
    // ==============================================================
    $(function () {
        var url = window.location;
        var element = $('ul#sidebarnav a').filter(function () {
            return this.href == url;
        }).addClass('active').parent().addClass('active');
        while (true) {
            if (element.is('li')) {
                element = element.parent().addClass('in').parent().addClass('active');
            } else {
                break;
            }
        }

    });
    // ==============================================================
    //tooltip
    // ==============================================================
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    // ==============================================================
    //Popover
    // ==============================================================
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    // ==============================================================
    // Sidebarmenu
    // ==============================================================
    $(function () {
        $('#sidebarnav').metisMenu();
    });
    // ==============================================================
    // Slimscrollbars
    // ==============================================================
    $('.scroll-sidebar').slimScroll({
        position: 'left'
        , size: "5px"
        , height: '100%'
        , color: '#dcdcdc'
    });
    $('.message-center').slimScroll({
        position: 'right'
        , size: "5px"

        , color: '#dcdcdc'
    });


    $('.aboutscroll').slimScroll({
        position: 'right'
        , size: "5px"
        , height: '80'
        , color: '#dcdcdc'
    });
    $('.message-scroll').slimScroll({
        position: 'right'
        , size: "5px"
        , height: '570'
        , color: '#dcdcdc'
    });
    $('.chat-box').slimScroll({
        position: 'right'
        , size: "5px"
        , height: '470'
        , color: '#dcdcdc'
    });

    $('.slimscrollright').slimScroll({
        height: '100%'
        , position: 'right'
        , size: "5px"
        , color: '#dcdcdc'
    });

    // ==============================================================
    // Resize all elements
    // ==============================================================
    $("body").trigger("resize");
    // ==============================================================
    // To do list
    // ==============================================================
    $(".list-task li label").click(function () {
        $(this).toggleClass("task-done");
    });

    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

    // ==============================================================
    // Collapsable cards
    // ==============================================================
    $('a[data-action="collapse"]').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ti-minus ti-plus');
        $(this).closest('.card').children('.card-body').collapse('toggle');

    });
    // Toggle fullscreen
    $('a[data-action="expand"]').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-compress');
        $(this).closest('.card').toggleClass('card-fullscreen');
    });

    // Close Card
    $('a[data-action="close"]').on('click', function () {
        $(this).closest('.card').removeClass().slideUp('fast');
    });
    // ==============================================================
    // This is for the sparkline charts which is coming in the bradcrumb section
    // ==============================================================
    $('#monthchart').sparkline([5, 6, 2, 9, 4, 7, 10, 12], {
        type: 'bar',
        height: '35',
        barWidth: '4',
        resize: true,
        barSpacing: '4',
        barColor: '#1e88e5'
    });
    $('#lastmonthchart').sparkline([5, 6, 2, 9, 4, 7, 10, 12], {
        type: 'bar',
        height: '35',
        barWidth: '4',
        resize: true,
        barSpacing: '4',
        barColor: '#7460ee'
    });
    var sparkResize;

});

//init editable
function buildEditable(elementName, termValue, title = 'Enter required field') {

    destroyEditable(elementName);

    $('#' + elementName).editable({
        type: 'text',
        title: title,
        name: elementName,
        value: termValue,
        mode: 'inline',
        sourceCache: true,
    });
}

//destroy editable
function destroyEditable(elementName) {
    resetEditableValue(elementName);
    $("#" + elementName).editable("destroy");
}

//reset editable value
function resetEditableValue(elementName) {
    $('#' + elementName).editable('setValue', null);
}

//disable button by id
function disableButton(el) {
    document.getElementById(el).disabled = true;
    $('#' + el).addClass('not-allowed-cursor');
}

//enable button by id
function enableButton(el) {
    document.getElementById(el).disabled = false;
    $('#' + el).removeClass('not-allowed-cursor');
}

function reloadMainDataTable() {
    $('#main-table').DataTable().ajax.reload();
}

function reloadTrashDataTable() {
    $('#trash-table').DataTable().ajax.reload();
}

function runSweetAlert(title, text, type, buttonClass = "btn-" + type, buttonText = "OK", closeClickOnConfirm = true) {
    swal({
        title: title,
        text: text,
        type: type,
        showCancelButton: false,
        confirmButtonClass: buttonClass,
        confirmButtonText: buttonText,
        closeOnConfirm: true,
    });
}

function sweet_error() {
    swal({
        title: "Sorry",
        text: "Something went wrong",
        type: "error",
        showCancelButton: false,
        confirmButtonClass: "btn-error",
        confirmButtonText: "OK",
        closeOnConfirm: true,
    });
}

function removeData(url, id) {
    swal({
            title: "Are you sure want to send data to Trash ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Send to Trash!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {

                $.ajax({
                    url: addSlash2Url(url),
                    type: "DELETE",
                    dataType: "JSON",
                    data: {"id": id, "_token": CSRF_TOKEN},
                    success: function (data) {
                        if (data.error === 0) {
                            runSweetAlert(data.message, "", "success");
                            reloadMainDataTable();
                        } else {
                            sweet_error();
                        }
                    },
                    error: function (response) {
                        sweet_error();
                    }
                });

            }
        }
    );
}

function removeDataPermanently(url, id) {
    swal({
            title: "Are you sure want to delete ?",
            text: "This operation can't be restored!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Delete forever!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {

                $.ajax({
                    url: addSlash2Url(url),
                    type: "DELETE",
                    dataType: "JSON",
                    data: {"id": id, "_token": CSRF_TOKEN},
                    success: function (response) {
                        if (response.error === 0) {
                            runSweetAlert(response.message, "", "success");
                            reloadTrashDataTable();
                            reloadMainDataTable();
                        } else {
                            sweet_error();
                        }
                    },
                    error: function (response) {
                        sweet_error();
                    }
                });

            }
        }
    );
}

function restoreData(url, id) {
    swal({
            title: "Are you sure want to restore?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Restore",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: addSlash2Url(url),
                    type: "POST",
                    dataType: "JSON",
                    data: {"id": id, "_token": CSRF_TOKEN},
                    success: function (response) {
                        if (response.error === 0) {
                            runSweetAlert(response.message, "", "success");
                            reloadTrashDataTable();
                        } else {
                            sweet_error();
                        }
                    },
                    error: function (response) {
                        sweet_error();
                    }
                });
            }
        }
    );
}


$('a[href="#trash"]').on('click', function (e) {
    if (!$.fn.dataTable.isDataTable('#trash-table')) {
        initTrashedTable()
    }
});

$('a[href="#main"]').on('click', function (e) {
    $('#trash').removeClass('active');
    $('#trash').addClass('hide');

});

function slugify(string) {
    const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;';
    const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------';
    const p = new RegExp(a.split('').join('|'), 'g');

    return string.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
        .replace(/&/g, '-and-') // Replace & with 'and'
        .replace(/[^\w\-]+/g, '') // Remove all non-word characters
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, ''); // Trim - from end of text
}

function initSwitchery() {
    $(".radio-switch").each(function () {
        $(this).bootstrapSwitch();
    });
}

//init select2
function initSelect2() {
    $(".select2").each(function () {
        $(this).select2();
    });
}

function trueFalseSetter(el) {
    let url = el.dataset.url;
    let isChecked = el.checked;
    let column = el.dataset.column;

    $.ajax({
        url: addSlash2Url(url),
        type: "POST",
        dataType: "JSON",
        data: {"isChecked": isChecked, "_token": CSRF_TOKEN, "column": column},
        success: function (response) {
            if (response.error === 0) {
                runSweetAlert(response.message, "", "success");
                reloadMainDataTable();
            } else {
                sweet_error();
            }
        },
        error: function (response) {
            sweet_error();
        }
    });
}

function hideElement(elementId) {
    $('#' + elementId).addClass('d-none');
}

function showElement(elementId) {
    $('#' + elementId).removeClass('d-none');
}

function initImagePicker(options = {}) {
    $('.image-picker').each(function () {
        $(this).imagepicker(options);
    });
}

function destroyImagePicker() {
    $('.image-picker').each(function () {
        $(this).data('picker').destroy();
    });
}

function selectAllSwitchery(el) {
    let elVal = $(el).prop('checked');

    $('.radio-switch').each(function (index) {
        $(this).bootstrapSwitch('state', elVal);
    });
}

function readOnlyCheckbox() {
    return false;
}

function removeRow(el, self = false, index = 1) {
    if (self === false) {
        let parents = $(el).parents();
        $(parents[index]).remove();
    } else {
        $(el).remove();
    }
}

function initSummerNote(options = {height: 350}) {
    $('.summernote').each(function () {
        $(this).summernote(options);
    })
}

function addSlash2Url(url) {

    if (url.match('\/$') === null) {
        return url + '/';
    }
    return url;

}

function initTagsInput() {

    let tagNames = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: addSlash2Url(route('admin.tag.find')),
            cache: false,
            transform: function (data) {
                return $.map(data, function (tagName, id) {
                    let keyWord = tagName.split('-');
                    return {name: keyWord.join(' '), id: id};
                });
            }
        }
    });

    tagNames.initialize();

    $('#tags').tagsinput({
        highlight: true,
        typeaheadjs: {
            name: 'tagName',
            displayKey: 'name',
            valueKey: 'name',
            source: tagNames.ttAdapter()
        }
    });
}

function fetchSubcategory(el) {

    let mainDiv = document.getElementById('subcategory-result').querySelectorAll('div');
    let currentValue = $(el).val();
    $(el).closest('div').nextAll('div.form-group').remove();

    if (currentValue == 0) {
        let oldSelect = $(el).closest('div').prev('div').find('select');
        setCategory(oldSelect);
        $(el).closest('div').remove();
        return true;
    }

    $.ajax({
        url: addSlash2Url(route("admin.category.subcategory", {category: currentValue})),
        type: "POST",
        data: {"_token": CSRF_TOKEN},
        success: function (response) {
            $('#subcategory-result').append(response);
        },
        error: function (response) {
            sweet_error();
        }
    });
}

// set category too all products
function setCategory(el) {
    let selectedText = $(el).children('option:selected').text();
    let selectedValue = $(el).children('option:selected').val();

    if (selectedValue == 0) {
        return true;
    }

    $('.product-category').each(function () {
        $(this).html('').trigger('change');
        let option = new Option(selectedText, selectedValue, true, true);
        $(this).append(option).trigger('change');
    });
}

$('#parentCategories').on('change', function () {
    $(this).nextAll('div').remove();
    fetchSubcategory(this);
});

