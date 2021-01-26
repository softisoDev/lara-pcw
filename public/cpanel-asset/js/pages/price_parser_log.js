$(document).ready(function () {
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        let pageNo = $(this).attr('href').split('page=')[1];
        fetchPriceParserLogs(pageNo);
    });

});

function fetchPriceParserLogs(pageNo = 1) {
    let dataLength = 25;

    let file_name = $('#log-files').val();

    $.ajax({
        url: addSlash2Url(route('admin.log.price.parser.data')),
        type: "POST",
        dataType: "JSON",
        data: {
            "_token": CSRF_TOKEN,
            "dataLength": dataLength,
            "logFile": file_name,
            "page": pageNo,
        },
        success:function (response) {
            console.log(response);
            $('#log-result').empty().html(response.result);
        },
        error:function (response) {
            console.log(response);
        }
    })
}
