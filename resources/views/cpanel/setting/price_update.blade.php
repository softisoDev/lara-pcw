@extends('cpanel.layouts.main')

@section('content')
    <section class="card card-body">
        <div class="row">
            <div class="col-md-3 col-lg-3"></div>
            <div class="col-md-6 col-lg-6">

                @include('cpanel.product_import.includes.error_template', ['errors' => $errors->all()])

                {!! Form::open(array('method' =>'POST', 'url' => addSlash2Url(route('admin.setting.price.updater.run')))) !!}

                <div class="form-group">
                    {!! Form::select('domains[]', $domains, null, array('class' => 'select2 select2-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Choose domain(s)')) !!}
                </div>

                <div class="form-group text-center">
                    <a href="{{addSlash2Url(route('admin.dashboard'))}}" class="btn btn-danger">Cancel</a>
                    {!! Form::submit('Run process', array('class'=>'btn btn-primary')) !!}
                </div>

                {!! Form::close() !!}
            </div>
            <div class="col-md-3 col-lg-3"></div>
        </div>
    </section>
@endsection

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/select2/dist/js/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            initSelect2();
        });
    </script>
@endpush

