@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        {{$dataTable->table()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('cpanel.source.modals.media_upload')
@endsection



@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/select2/dist/js/select2.full.min.js')}}"></script>
    {!! $dataTable->scripts() !!}
    <script src="{!! asset('cpanel-asset/plugins/tables/js/datatable/action_button_selection.js') !!}"></script>
    <script src="{{asset('cpanel-asset/js/pages/source_index.js')}}"></script>
@endpush
