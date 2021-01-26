@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4"></div>
        <div class="col-lg-4 col-md-4">
            {!! Form::select('logFiles', $files, null, array('class' => 'form-control', 'id' => 'log-files', 'onchange'=>'fetchPriceParserLogs()')) !!}
        </div>
        <div class="col-lg-4 col-md-4"></div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body" id="log-result">

                </div>
            </div>
        </div>
    </div>

@endsection

@push('add-script')
    <script src="{{asset('cpanel-asset/js/pages/price_parser_log.js')}}"></script>
@endpush
