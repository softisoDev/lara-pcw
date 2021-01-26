@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <!-- Product id -->
                        {!! Form::hidden('product_id', $product->id, array('id' => 'product_id')) !!}

                        <div class="col-md-12 col-lg-12">
                            <h5 class="label-text-color">Upload new image</h5>

                            {!! Form::open(array('method' => 'post', 'url' => addSlash2Url(route('admin.product.image.upload', $product->id)), 'id' => 'mediaUploadForm', 'class' => 'dropzone', "enctype" => "multipart/form-data")) !!}
                            <div class="fallback">
                                {!! Form::file('image') !!}
                            </div>
                            {!! Form::close(); !!}
                        </div>
                        <div class="col-md-12 col-lg-12 mt-3 text-right">
                            {!! Form::button('Upload', array('class' => 'btn btn-success', 'id' => 'upload-media-btn')) !!}
                        </div>
                    </div>

                    <!-- media datatable -->
                    <div class="row">
                        <div class="table-responsive text-nowrap">
                            {{$dataTable->table()}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/dropzone/min/dropzone.min.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/dropzone/dropzone.js')}}"></script>
    {!! $dataTable->scripts() !!}
    <script src="{{asset('cpanel-asset/js/pages/product_image.js')}}"></script>

@endpush
