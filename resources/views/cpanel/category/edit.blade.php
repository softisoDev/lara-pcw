@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row pt-3">
                        <div class="col hidden-sm-down"></div>
                        <div class="col-md-6">
                            {!! Form::open(array('method' =>'POST', 'url' =>  addSlash2Url(route('admin.category.update', $category->id)), 'class'=>'form-material', 'enctype' => 'multipart/form-data')) !!}
                            @method('PUT')

                            {{--  Show on homepage  --}}
                            <div class="form-group text-left">
                                {!! Form::label('showOnHomepage', 'Show on homepage') !!}
                                {!! Form::select("showOnHomepage", $trueFalse, $category->show_on_homepage, array('class' => "form-control")) !!}
                            </div>

                            {{-- name --}}
                            <div class="form-group text-left">
                                {!! Html::decode(Form::label('name','Category name <span class="text-red">*</span>')) !!}
                                {!! Form::text('name', $category->name, array('class'=>'form-control', 'id'=>'category_name', 'onfocusout' => 'outFocusCategory(this)')) !!}
                                @error('name')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            {{--  slug  --}}
                            <div class="form-group">
                                {!! Html::decode(Form::label('slug','Slug <span class="text-red">*</span>')) !!}
                                {!! Form::text('slug', $category->slug, array('id' => 'slug', 'class'=>'form-control')) !!}
                                @error('slug')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            {{-- title --}}
                            <div class="form-group text-left">
                                {!! Html::decode(Form::label('title','Category title <span class="text-red">*</span>')) !!}
                                {!! Form::text('title', $category->title, array('class'=>'form-control', 'id'=>'category_title')) !!}
                                @error('title')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            {{-- description --}}
                            <div class="form-group text-left">
                                {!! Form::label('description', 'Description') !!}
                                {!! Form::textarea('description', $category->description, array('class'=>'form-control')) !!}
                            </div>

                            {{-- content --}}
                            <div class="form-group text-left">
                                <h5 class="label-text-color">Content</h5>
                                {!! Form::textarea('content', $category->content, array('id'=>'content', 'class'=>'form-control')) !!}
                            </div>

                            {{-- image --}}
                            <div class="form-group text-left ">
                                <h5 class="label-text-color">Image</h5>

                                {!! Form::file('image',['id' => 'image', 'class'=> 'form-control dropify', 'data-max-file-size'=>'1M', 'data-default-file'=>$mediaPath, 'data-allowed-file-extensions' => 'jpg png']) !!}
                            </div>

                            {{-- image title --}}
                            <div class="form-group text-left">
                                {!! Form::label('image_title', 'Image title') !!}
                                {!! Form::text('image_title', $mediaTitle, array('class'=>'form-control', 'max'=>150, 'id'=>'image-title')) !!}
                                @error('image_title')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            {{-- save --}}
                            <div class="form-group text-right">
                                <a href="{{addSlash2Url(route('admin.category.index'))}}" class="btn btn-danger">Cancel</a>
                                {!! Form::submit('Update', array('class'=>'btn btn-primary', 'id'=>'form-submit-button')) !!}
                            </div>
                            {!! Form::close() !!}

                        </div>
                        <div class="col hidden-sm-down"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/summernote/dist/summernote.css')}}" rel="stylesheet">
    <link href="{{asset('cpanel-asset/plugins/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset('cpanel-asset/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/dropify/dist/js/dropify.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/js/pages/category.js')}}"></script>
@endpush


