@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="main-table"
                               class="table w-100 display nowrap table-striped table-bordered scroll-horizontal-vertical base-style dtTable">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Image</th>
                                <th>Source Name</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>id</th>
                                <th>Image</th>
                                <th>Source Name</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('cpanel.product_variation.modals.source_media_upload')
@endsection



@push('add-script')
    <script src="{{asset('cpanel-asset/js/pages/source.js')}}"></script>
@endpush
