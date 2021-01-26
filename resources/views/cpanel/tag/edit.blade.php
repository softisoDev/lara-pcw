@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            {!! Form::open(array('method' =>'POST', 'url' => addSlash2Url(route('admin.tag.update', array('tag' => $tag->id))), 'class'=>'form-material')) !!}
                            @method('PUT')

                            {!! Form::hidden('status', true) !!}

                            {{-- slug --}}
                            <div class="form-group">
                                {!! Html::decode(Form::label('slug','Tag <span class="text-red">*</span>')) !!}
                                {!! Form::text('slug', $tag->slug, array('class' => 'form-control', 'id' => 'slug')) !!}
                                @error('slug')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            {{-- title --}}
                            <div class="form-group text-left">
                                {!! Html::decode(Form::label('title','Tag title')) !!}
                                {!! Form::text('title', $tag->title, array('class'=>'form-control', 'id'=>'category_title')) !!}
                                @error('title')<small class="text-red">{{$message}}</small>@enderror
                            </div>

                            {{-- description --}}
                            <div class="form-group text-left">
                                {!! Form::label('description', 'Description') !!}
                                {!! Form::textarea('description', $tag->description, array('class'=>'form-control')) !!}
                            </div>

                            {{-- content --}}
                            <div class="form-group text-left">
                                <h5 class="label-text-color">Content</h5>
                                {!! Form::textarea('content', $tag->content, array('id'=>'content', 'class'=>'form-control')) !!}
                            </div>

                            <div class="form-group text-center">
                                <a href="{{addSlash2Url(route('admin.tag.index'))}}" class="btn btn-danger">Cancel</a>
                                {!! Form::submit('Update', array('class'=>'btn btn-primary', 'id'=>'form-submit-button')) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>

                        <div class="col-lg-4 col-md-4 pl-5">
                            {!! Form::open(array('id' => 'query-form', 'method' =>'POST', 'url' => addSlash2Url(route('admin.tag.option.update',  array('tag' => $tag->id))), 'class'=>'form-material')) !!}
                            <div class="col-12" id="subcategory-result">
                                <div>
                                    {!! Form::select('category[]', $categories, isset($cAncestors) ? $cAncestors[0]->id : null, array('class' => 'form-control', 'id' => 'parentCategories')) !!}
                                    @error('category.*')<small class="text-red">{{$message}}</small>@enderror
                                </div>
                                @isset($cAncestors)
                                    @include('cpanel.category.includes.build_tree', ['categories' => $cAncestors])
                                @endisset
                            </div>

                            <div class="col-12 mt-3">

                                <div class="form-group">
                                    {!! Form::label('query', 'Query') !!}
                                    {!! Form::text('query', isset($query) ? $query : $tag->slug, array('class' => 'form-control')) !!}
                                    @error('query')<small class="text-red">{{$message}}</small>@enderror
                                </div>

                                <div class="form-group text-right">
                                    {!! Form::submit('Update query', array('class'=>'btn btn-success', 'id'=>'query_submit')) !!}
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/summernote/dist/summernote.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/js/pages/tag.js')}}"></script>
@endpush
