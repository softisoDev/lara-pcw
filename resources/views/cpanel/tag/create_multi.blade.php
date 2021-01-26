@extends('cpanel.layouts.main')

@section('content')

    <section class="card card-body">
        <div class="row">
            <div class="col-md-4 col-lg-4"></div>
            <div class="col-md-4 col-lg-4">
                @include('cpanel.product_import.includes.error_template', ['errors' => $errors->all()])
            </div>
            <div class="col-md-4 col-lg-4"></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                {!! Form::open(array('method' =>'POST', 'url' => addSlash2Url(route('admin.tag.multi.store')), 'id'=>'multi-create-form')) !!}

                <div class="row p-3">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 col-lg-4" id="subcategory-result">
                        {!! Form::select('category[]', $categories, null, array('class' => 'form-control', 'id' => 'parentCategories')) !!}
                    </div>
                    <div class="col-md-4"></div>
                </div>

                <div class="row p-3">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">

                        <div class="form-group">
                            {!! Form::label('tags', 'Tags') !!}
                            {!! Form::textarea('keywords', null, array('class'=>'form-control')) !!}
                        </div>

                        <div class="form-group text-center">
                            {!! Form::submit('Save', array('class'=>'btn btn-primary', 'name' => 'multi-create-form')) !!}
                        </div>

                    </div>
                    <div class="col-md-3"></div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection


@push('add-script')
    <!-- custom -->
    <script src="{{asset('cpanel-asset/js/pages/tag.js')}}"></script>
@endpush
