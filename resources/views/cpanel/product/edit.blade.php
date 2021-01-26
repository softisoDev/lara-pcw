@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <ul class="nav nav-tabs" id="productTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab"
                               aria-controls="main" aria-selected="true"><i class="fa fa-bars"></i> Main</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="variation-tab" data-toggle="tab" href="#variation" role="tab"
                               aria-controls="media" aria-selected="false"><i class="fa fa-sitemap"></i> Variations</a>
                        </li>
                    </ul>


                    {!! Form::open(array('method' =>'POST', 'url' =>  addSlash2Url(route('admin.product.update', $product->id)), 'class'=>'form-material', 'enctype' => 'multipart/form-data')) !!}

                    <div class="tab-content p-5" id="productTabContent">

                        <!-- main tab -->
                        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                        @method('PUT')

                        <!-- Product id -->
                        {!! Form::hidden('product_id', $product->id, array('id' => 'product_id')) !!}

                        <!-- Product title -->
                            <div class="form-group">
                                {!! Form::label('title', 'Title') !!}
                                {!! Form::text('title', $product->title, array('class' => 'form-control', 'placeholder' => 'Product name')) !!}
                                @error('title')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            <!-- Main details -->
                            <div class="row">

                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        {!! Form::label('brand', 'Brand') !!}
                                        {!! Form::select('brand', $brand, $product->brand->id, array('class' => 'select2')) !!}
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-3" id="subcategory-result">
                                    <div class="form-group">
                                        {!! Form::label('parentCategories', 'Set Category') !!}
                                        {!! Form::select('category[]', $category, $cAncestors[0]->id, array('class' => 'form-control', 'id' => 'parentCategories', 'onchange' => 'setCategory(this)')) !!}
                                    </div>
                                    @include('cpanel.category.includes.build_tree', ['categories' => $cAncestors])
                                </div>

                                <div class="col-md-3 col-lg-3">
                                    {!! Form::label('manufacturer', 'Manufacturer') !!}
                                    {!! Form::text('manufacturer', $product->manufacturer, array('class' => 'form-control')) !!}
                                </div>

                                <div class="col-md-2 col-lg-2">
                                    {!! Form::label('weight', 'Weight') !!}
                                    {!! Form::text('weight', $product->weight, array('class' => 'form-control')) !!}
                                </div>

                                <div class="col-md-2 col-lg-2">
                                    {!! Form::label('dimensions', 'Dimensions') !!}
                                    {!! Form::text('dimensions', $product->dimensions, array('class' => 'form-control')) !!}
                                </div>

                            </div>

                            <!-- Codes and Features -->
                            <div class="row mt-3">

                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">

                                        @foreach($product->codes as $key => $code)
                                            <div class="form-group m-0 row">
                                                {!! Form::label($key, ucfirst($key), array('class' => 'col-3 col-form-label')) !!}
                                                <div class="col-9">
                                                    {!! Form::text('codes['.$key.']', (!is_array($code)) ? implode(', ', array($code)) : implode(', ', $code), array('class' => 'form-control', 'placeholder' => $key, 'id' => $key)) !!}
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                <div class="col-md-8 col-lg-8">
                                    <div class="form-group" id="feature-fields">
                                        @if(!is_null($features))
                                            @foreach($features as $feature)

                                                <div class="form-group m-0 row">
                                                    <div class="col-11">
                                                        {!! Form::text('features[]', $feature, array('class' => 'form-control')) !!}
                                                    </div>
                                                    <div class="col-1 pt-2"><i
                                                            class="fa fa-trash text-red cursor-pointer"
                                                            onclick="removeRow(this, false)"></i></div>
                                                </div>

                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="form-group text-right">
                                        {!! Form::button('+ Add feature', array('class'=>'btn btn-primary', 'onclick' => 'addFeatureField()')) !!}
                                    </div>

                                </div>

                            </div>

                            <!-- Description -->
                            <div class="row mt-3">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <h5 class="label-text-color">Description</h5>
                                        {!! Form::textarea('description', $product->description, array('class'=>'form-control summernote')) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- Header description -->
                            <div class="row mt-3">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <h5 class="label-text-color">Header content</h5>
                                        {!! Form::textarea('meta_description', $product->meta_description, array('class'=>'form-control summernote')) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- tags -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    {!! Form::label('tags', 'Tags') !!}
                                    {!! Form::text('tags', $product->getTagForInput(), array('class' => 'form-control mb-3', 'id' => 'tags', 'autocomplete'=>'off')) !!}
                                </div>
                            </div>

                            <!-- submit/cancel -->
                            <div class="row mt-3">
                                <div class="col-md-12 col-lg-12 text-right">
                                    <a href="{{route('admin.product.index')}}" class="btn btn-danger">Cancel</a>
                                    {!! Form::submit('Update', array('class' => 'btn btn-primary')) !!}
                                </div>
                            </div>


                        </div>

                        {!! Form::close() !!}


                        <div class="tab-pane fade" id="variation" role="tabpanel" aria-labelledby="variation-tab">

                            <!--  -->
                            <div class="row">

                                <div class="table-responsive text-nowrap">
                                    <a href="{{route('admin.product.create')}}" target="_blank"
                                       class="btn btn-sm btn-primary mb-3 pull-right"><i class="fa fa-plus"></i> Add new
                                        variation</a>
                                    <table id="main-table"
                                           class="table w-100 display nowrap table-striped table-bordered scroll-horizontal-vertical base-style dtTable">
                                        <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Title</th>
                                            <th>Source</th>
                                            <th>Color</th>
                                            <th>Current price</th>
                                            <th>Price max</th>
                                            <th>Price min</th>
                                            <th>Availability</th>
                                            <th>Size</th>
                                            <th>Merchant</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr>
                                            <th>id</th>
                                            <th>Title</th>
                                            <th>Source</th>
                                            <th>Color</th>
                                            <th>Current price</th>
                                            <th>Price max</th>
                                            <th>Price min</th>
                                            <th>Availability</th>
                                            <th>Size</th>
                                            <th>Merchant</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>
@endsection

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/summernote/dist/summernote.css')}}" rel="stylesheet">
    <link href="{{asset('cpanel-asset/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <!--  Tags input  -->
    <link href="{{asset('cpanel-asset/plugins/bootstrap-tagsinput/src/bootstrap-tagsinput.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <!--  Tags input  -->
    <script src="{{asset('cpanel-asset/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/typeahead.js-master/dist/typeahead.bundle.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/js/pages/product.js')}}"></script>
@endpush
