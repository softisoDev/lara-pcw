@extends('cpanel.layouts.main')

@section('content')

    {{--  error area  --}}
    <div class="row">
        <div class="col-md-12 col-lg-12 text-center" id="error-area">

        </div>
    </div>

    {{-- Search and filter area   --}}
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#new-search" role="tab"><span
                                class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">New search</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#from-saved-search" role="tab"><span
                                class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">From saved search</span></a>
                    </li>

                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    {{--  Search from api tab --}}
                    <div class="tab-pane active" id="new-search" role="tabpanel">
                        {!! Form::open(array('method' =>'POST', 'url' => addSlash2Url(route('admin.product.import.search')), 'id' => 'search-form')) !!}
                        {{--  Search area  --}}
                        <div class="row p-3">
                            {{--  search input  --}}
                            <div class="col-md-6 col-lg-6">
                                <div class="input-group">
                                    {!! Form::text('term', null, array('class' => 'form-control', 'placeholder'=>'Search for...', 'autocomplete' => 'off')) !!}
                                    <span class="input-group-btn">
                                    {!! Form::submit('Search', array('class' => 'btn btn-primary btn-product-import-search')) !!}
                                </span>
                                </div>
                            </div>

                            {{--  search source(s)  --}}
                            <div class="col-md-5 col-lg-5 pt-1">
                                {!! Form::select('sources[]', $sources, null, array('class' => 'select2 select2-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Choose source(s)')) !!}
                            </div>

                            {{--  number of records  --}}
                            <div class="col-lg-1 col-md-1">
                                {!! Form::number('numRecords', 10, array('class' => 'form-control', 'min' => 1, 'step' => 1)) !!}
                            </div>

                            {!! Form::hidden('searchName', null, array('id' => 'searchName')) !!}
                            {!! Form::hidden('tempDir', true, array('id' => 'tempDir')) !!}
                        </div>

                        {{--  Search area  --}}
                        <div class="row pl-3 pr-3">

                            {{--  Manufacturer  --}}
                            <div class="col-lg-4 col-md-4">
                                {!! Form::text('manufacturer', null, array('class' => 'form-control', 'placeholder' => 'Manufacturer', 'autocomplete' => 'off')) !!}
                            </div>

                            {{--  Categories  --}}
                            <div class="col-lg-4 col-md-4 pt-1">
                                {!! Form::select('primaryCategory', $apiCategories, null, array('class' => 'select2', 'placeholder' => 'Choose category')) !!}
                            </div>

                            {{--  last updated date  --}}
                            <div class="col-lg-4 col-md-4">
                                {!! Form::text('dateUpdated', null, array('class'=>'form-control', 'id' => 'mdate', 'placeholder' => 'Choose date')) !!}
                            </div>
                        </div>

                        {{--  Filter area  --}}
                        <div class="row pl-3 pr-3" id="search-area">
                            <div class="col-md-12 col-lg-12">
                                <div id="accordion-field" class="accordion" role="tablist" aria-multiselectable="false">
                                    <div class="mt-3 mb-3">
                                        <h3>
                                            <a data-toggle="collapse" data-parent="#require-fields"
                                               href="#collapse-field"
                                               aria-expanded="true" aria-controls="require-fields">
                                                Required Fields
                                            </a>
                                        </h3>
                                    </div>

                                    <div id="collapse-field" class="collapse" role="tabpanel"
                                         aria-labelledby="headingOne">
                                        <div class="demo-checkbox">
                                            <div class="form-group mb-0">
                                                <h4>Codes</h4>

                                                {{--  ASINs  --}}
                                                {!! Form::checkbox('fields[]', 'asins:*', false, array('class' => 'filled-in', 'id' => 'filter-asin')) !!}
                                                {!! Form::label('filter-asin', 'ASINs') !!}

                                                {{--  ISBN  --}}
                                                {!! Form::checkbox('fields[]', 'isbn:*', false, array('class' => 'filled-in', 'id' => 'filter-isbn')) !!}
                                                {!! Form::label('filter-isbn', 'ISBN') !!}

                                                {{--  UPC  --}}
                                                {!! Form::checkbox('fields[]', 'upc:*', false, array('class' => 'filled-in', 'id' => 'filter-upc')) !!}
                                                {!! Form::label('filter-upc', 'UPC') !!}

                                                {{--  EAN  --}}
                                                {!! Form::checkbox('fields[]', 'ean:*', false, array('class' => 'filled-in', 'id' => 'filter-ean')) !!}
                                                {!! Form::label('filter-ean', 'EAN') !!}

                                                {{--  SKUs  --}}
                                                {!! Form::checkbox('fields[]', 'skus:*', false, array('class' => 'filled-in', 'id' => 'filter-sku')) !!}
                                                {!! Form::label('filter-sku', 'SKUs') !!}

                                                {{--  VIN  --}}
                                                {!! Form::checkbox('fields[]', 'skus:*', false, array('class' => 'filled-in', 'id' => 'filter-vin')) !!}
                                                {!! Form::label('filter-vin', 'VIN') !!}

                                            </div>

                                            <div class="form-group  mb-0">
                                                <h4>Others</h4>

                                                {{--  Name  --}}
                                                {!! Form::checkbox('fields[]', 'name:*', false, array('class' => 'filled-in', 'id' => 'filter-name')) !!}
                                                {!! Form::label('filter-name', 'Name') !!}

                                                {{--  Brands  --}}
                                                {!! Form::checkbox('fields[]', 'brand:*', false, array('class' => 'filled-in', 'id' => 'filter-brand')) !!}
                                                {!! Form::label('filter-brand', 'Brand') !!}

                                                {{--  Primary categories  --}}
                                                {!! Form::checkbox('fields[]', 'primaryCategories:*', false, array('class' => 'filled-in', 'id' => 'filter-category')) !!}
                                                {!! Form::label('filter-category', 'Primary categories') !!}

                                                {{--  Colors  --}}
                                                {!! Form::checkbox('fields[]', 'colors:*', false, array('class' => 'filled-in', 'id' => 'filter-color')) !!}
                                                {!! Form::label('filter-color', 'Colors') !!}

                                                {{--  Prices  --}}
                                                {!! Form::checkbox('fields[]', 'prices:*', true, array('class' => 'filled-in', 'id' => 'filter-price', 'onClick' => 'return readOnlyCheckbox()')) !!}
                                                {!! Form::label('filter-price', 'Prices') !!}

                                                {{--  Count  --}}
                                                {!! Form::checkbox('fields[]', 'count:*', false, array('class' => 'filled-in', 'id' => 'filter-count')) !!}
                                                {!! Form::label('filter-count', 'Count') !!}

                                                {{--  Descriptions  --}}
                                                {!! Form::checkbox('fields[]', 'descriptions:*', false, array('class' => 'filled-in', 'id' => 'filter-description')) !!}
                                                {!! Form::label('filter-description', 'Descriptions') !!}

                                                {{--  Dimensions  --}}
                                                {!! Form::checkbox('fields[]', 'dimension:*', false, array('class' => 'filled-in', 'id' => 'filter-dimension')) !!}
                                                {!! Form::label('filter-dimension', 'Dimensions') !!}

                                                {{--  Features  --}}
                                                {!! Form::checkbox('fields[]', 'features:*', false, array('class' => 'filled-in', 'id' => 'filter-features')) !!}
                                                {!! Form::label('filter-features', 'Features') !!}

                                                {{--  Flavors  --}}
                                                {!! Form::checkbox('fields[]', 'flavors:*', false, array('class' => 'filled-in', 'id' => 'filter-flavor')) !!}
                                                {!! Form::label('filter-flavor', 'Flavors') !!}

                                                {{--  Image URLs  --}}
                                                {!! Form::checkbox('fields[]', 'imageURLs:*', true, array('class' => 'filled-in', 'id' => 'filter-image-url', 'onClick' => 'return readOnlyCheckbox()')) !!}
                                                {!! Form::label('filter-image-url', 'Image URLs') !!}

                                                {{--  Merchants  --}}
                                                {!! Form::checkbox('fields[]', 'merchants:*', false, array('class' => 'filled-in', 'id' => 'filter-merchant')) !!}
                                                {!! Form::label('filter-merchant', 'Merchants') !!}

                                                {{--  Manufacturer  --}}
                                                {!! Form::checkbox('fields[]', 'manufacturer:*', false, array('class' => 'filled-in', 'id' => 'filter-manufacturer')) !!}
                                                {!! Form::label('filter-manufacturer', 'Manufacturer') !!}

                                                {{--  Manufacturer number  --}}
                                                {!! Form::checkbox('fields[]', 'manufacturerNumber:*', false, array('class' => 'filled-in', 'id' => 'filter-manufacturer-number')) !!}
                                                {!! Form::label('filter-manufacturer-number', 'Manufacturer number') !!}

                                                {{--  Quantities  --}}
                                                {!! Form::checkbox('fields[]', 'quantities:*', false, array('class' => 'filled-in', 'id' => 'filter-quantity')) !!}
                                                {!! Form::label('filter-quantity', 'Quantities') !!}

                                                {{--  Reviews  --}}
                                                {!! Form::checkbox('fields[]', 'reviews:*', false, array('class' => 'filled-in', 'id' => 'filter-review')) !!}
                                                {!! Form::label('filter-review', 'Reviews') !!}

                                                {{--  Sizes  --}}
                                                {!! Form::checkbox('fields[]', 'sizes:*', false, array('class' => 'filled-in', 'id' => 'filter-size')) !!}
                                                {!! Form::label('filter-size', 'Sizes') !!}

                                                {{--  Source URLs  --}}
                                                {!! Form::checkbox('fields[]', 'sourceURLs:*', true, array('class' => 'filled-in', 'id' => 'filter-source-url', 'onClick' => 'return readOnlyCheckbox()')) !!}
                                                {!! Form::label('filter-source-url', 'Source URLs') !!}

                                                {{--  Website IDs  --}}
                                                {!! Form::checkbox('fields[]', 'websiteIDs:*', false, array('class' => 'filled-in', 'id' => 'filter-website-id')) !!}
                                                {!! Form::label('filter-website-id', 'Website IDs') !!}

                                                {{--  Weight  --}}
                                                {!! Form::checkbox('fields[]', 'weight:*', false, array('class' => 'filled-in', 'id' => 'filter-weight')) !!}
                                                {!! Form::label('filter-weight', 'Weight') !!}

                                                {{--  Date added  --}}
                                                {!! Form::checkbox('fields[]', 'dateAdded:*', false, array('class' => 'filled-in', 'id' => 'filter-date-added')) !!}
                                                {!! Form::label('filter-date-added', 'Date added') !!}

                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>

                    {{--  Search from folder tab  --}}
                    <div class="tab-pane  p-3" id="from-saved-search" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 col-lg-4"></div>
                            <div class="col-md-4 col-lg-4">
                                <div class="form-group">
                                    {!! Form::select('savedSearch', $savedSearch, '', array('class' => 'form-control select2', 'id' => 'fileName')) !!}
                                </div>
                                <div class="form-group text-center">
                                    <button type="button" onclick="fetchDataByFile()" class="btn btn-primary">Show
                                        result
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {!! Form::open(array('method' => 'POST', 'url' => addSlash2Url(route('admin.product.import.save')), 'id' => 'result-form', 'enctype' => 'multipart/form-data')) !!}

    {{--  Processing area  --}}
    <div class="row d-none" id="processing-area">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body pb-0">

                    <div class="row">

                        <div class="col-lg-1 col-md-1">
                            <div class="form-group">
                                {!! Form::select('dataLength', $dataLength, 10, array('class' => 'form-control', 'onchange' => 'fetchData()', 'id' => 'dataLength')) !!}
                            </div>
                        </div>

                        <div class="col-lg-1 col-md-1 pt-2">
                            <div class="form-group">
                                {!! Form::checkbox('selectAll', '', true, array('id' => 'selectAll', 'onclick' => 'selectAllSwitchery(this)')) !!}
                                {!! Form::label('selectAll', 'Select all') !!}
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <p class="text-center"><span class="font-weight-bold" id="parsed-result">0</span> result
                                parsed</p>
                        </div>

                        <div class="col-lg-4 col-md-4 text-right">

                            {{--  Import data  --}}
                            <button type="submit" form="result-form" class="btn btn-primary"><i
                                    class="fa fa-download"></i> Import
                            </button>

                            {{--  Save search button  --}}
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#saveSearchModal"
                               class="btn btn-primary">
                                <i class="fa fa-save"></i> Save this search
                            </a>

                            {{--  refresh button  --}}
                            <button type="button" class="btn btn-success" onclick="fetchData()"><i
                                    class="fa fa-refresh"></i> Refresh
                            </button>

                        </div>

                    </div>

                    <div class="row pb-3">
                        <div class="col-md-4 col-lg-4" id="subcategory-result">
                            {!! Form::label('parentCategories', 'Set Category') !!}
                            {!! Form::select('category[]', $categories, null, array('class' => 'form-control', 'id' => 'parentCategories', 'onchange' => 'setCategory(this)')) !!}
                        </div>

                        <div class="col-md-8 col-lg-8">
                            <div class="form-group">
                                {!! Form::label('tags', 'Set tags') !!}
                                {!! Form::text('tags', null, array('class' => 'form-control', 'id' => 'tags', 'autocomplete'=>'off', 'data-role'=>'tagsinput')) !!}
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    {{--  Result area  --}}
    <div class="row" id="result-area"></div>

    {!! Form::close() !!}

@endsection

@include('cpanel.product_import.modals.save_file')

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link
        href="{{asset('cpanel-asset/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}"
        rel="stylesheet">
    <link href="{{asset('cpanel-asset/plugins/image-picker/css/image-picker.css')}}" rel="stylesheet">
    {{--  Tags input  --}}
    <link href="{{asset('cpanel-asset/plugins/bootstrap-tagsinput/src/bootstrap-tagsinput.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/image-picker/js/image-picker.js')}}"></script>
    {{--  Tags input  --}}
    <script src="{{asset('cpanel-asset/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/typeahead.js-master/dist/typeahead.bundle.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/luckmoshy/luckmoshyJqueryPagnation.js')}}"></script>
    <script src="{{asset('cpanel-asset/js/pages/product_import.js')}}"></script>
@endpush

