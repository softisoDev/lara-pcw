@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="p-2 text-center">
                        <button type="button" class="btn btn-primary" onclick="updateTree()">Update tree</button>
                    </div>

                    <div class="myadmin-dd-empty dd" id="nestable2">
                        <ol class="dd-list">

                            @foreach($categories as $category)
                                <li class="dd-item dd3-item" data-id="{{$category->id}}" data-title="{{$category->name}}">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="dd3-content"> {{$category->name}}</div>
                                    @if(count($category->children) > 0)
                                        <ol class="dd-list">
                                            @foreach($category->children as $child)
                                                @include('cpanel.category.includes.order_nest', $child)
                                            @endforeach
                                        </ol>
                                    @endif
                                </li>
                            @endforeach

                        </ol>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('add-style')
    {{--  nestable  --}}
    <link href="{{asset('cpanel-asset/plugins/nestable/nestable.css')}}" rel="stylesheet">
@endpush

@push('add-script')
    {{--  nestable  --}}
    <script src="{{asset('cpanel-asset/plugins/nestable/jquery.nestable.js')}}"></script>
    <script src="{{asset('cpanel-asset/js/pages/category_order.js')}}"></script>
@endpush

