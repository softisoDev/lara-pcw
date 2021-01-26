<div class="row">
    <div class="col-lg-12 col-md-12 text-right">
        <button type="button" class="btn btn-success mt-1" onclick="fetchPriceParserLogs()"><i class="fa fa-refresh"></i> Refresh</button>
        &nbsp;
        <span class="pull-right">
            {{$data->links()}}
        </span>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive text-nowrap">
            <table
                class="table w-100 display nowrap table-striped table-bordered scroll-horizontal-vertical base-style dtTable">
                <thead>
                <tr>
                    <th>Product id</th>
                    <th>Variation id</th>
                    <th>Url</th>
                    <th>Error message</th>
                    <th>Date</th>
                </tr>
                </thead>

                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item['product_id']}}</td>
                        <td>{{$item['variation_id']}}</td>
                        <td><a href="{{$item['url']}}" target="_blank">Visit url</a> </td>
                        <td>{{$item['error_info']['message']}}</td>
                        <td>{{$item['date']}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>
