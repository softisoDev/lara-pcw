@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="tab-pane active p-10" id="create" role="tabpanel">

                        <div class="row pt-3">
                            <div class="col-lg-3 col-md-3"></div>
                            <div class="col-lg-6 col-md-6">
                                {!! Form::open(array('method' =>'POST', 'url' => addSlash2Url(route('admin.tag.store')), 'class'=>'form-material')) !!}

                                {!! Form::hidden('status', true) !!}

                                {{-- keyword --}}
                                <div class="form-group">
                                    {!! Html::decode(Form::label('slug','Tag <span class="text-red">*</span>')) !!}
                                    {!! Form::text('slug', null, array('class' => 'form-control', 'id' => 'keyword')) !!}
                                    @error('keyword')<small class="text-red">{{$message}}</small>@enderror
                                </div>


                                {{-- title --}}
                                <div class="form-group text-left">
                                    {!! Html::decode(Form::label('title','Tag title')) !!}
                                    {!! Form::text('title', null, array('class'=>'form-control', 'id'=>'category_title')) !!}
                                    @error('title')<small class="text-red">{{$message}}</small>@enderror
                                </div>

                                {{-- description --}}
                                <div class="form-group text-left">
                                    {!! Form::label('description', 'Description') !!}
                                    {!! Form::textarea('description', null, array('class'=>'form-control')) !!}
                                </div>

                                {{-- content --}}
                                <div class="form-group text-left">
                                    <h5 class="label-text-color">Content</h5>
                                    {!! Form::textarea('content', null, array('id'=>'content', 'class'=>'form-control')) !!}
                                </div>

                                <div class="form-group text-center">
                                    <a href="{{addSlash2Url(route('admin.tag.index'))}}" class="btn btn-danger">Cancel</a>
                                    {!! Form::submit('Save', array('class'=>'btn btn-primary', 'id'=>'form-submit-button')) !!}
                                </div>

                                {!! Form::close() !!}
                            </div>
                            <div class="col-lg-3 col-md-3"></div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('add-style')
    <link href="{{asset('cpanel-asset/plugins/summernote/dist/summernote.css')}}" rel="stylesheet">
    <!--  Tags input  -->
    <link href="{{asset('cpanel-asset/plugins/bootstrap-tagsinput/src/bootstrap-tagsinput.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    <script src="{{asset('cpanel-asset/plugins/summernote/dist/summernote.min.js')}}"></script>

    <!--  Tags input  -->
    <script src="{{asset('cpanel-asset/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('cpanel-asset/plugins/typeahead.js-master/dist/typeahead.bundle.min.js')}}"></script>

    <!-- custom -->
    <script src="{{asset('cpanel-asset/js/pages/tag.js')}}"></script>
@endpush
