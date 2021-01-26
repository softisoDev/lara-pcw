@extends('cpanel.layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 hidden-lg-down"></div>
                        <div class="col-md-6">
                            <div class="table-responsive d-flex justify-content-center">
                                <table class="table table-bordered w-100">

                                    <tbody>
                                    <tr>
                                        <td class="col-3"><strong>Parent brand: </strong></td>
                                        <td>{{(!is_null($brand->parent)) ? $brand->parent->name : "Parent brand"}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Name: </strong></td>
                                        <td>{{$brand->name}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Subtitle: </strong></td>
                                        <td>{{$brand->slug}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Subtitle: </strong></td>
                                        <td>{{$brand->subtitle}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Description: </strong></td>
                                        <td>{!! $brand->description !!}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Image: </strong></td>
                                        <td class="text-center"><img
                                                src="{{asset('uploads/images/brand/'.$brand->media->file_name)}}"
                                                class="img-responsive w-25"></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Created: </strong></td>
                                        <td>{{$brand->created_at}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Last update time: </strong></td>
                                        <td>{{$brand->updated_at}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Deleted date: </strong></td>
                                        <td>{{$brand->deleted_at}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <a href="{{addSlash2Url(route('admin.brand.index'))}}" class="btn btn-primary">Back to brand</a>
                            </div>
                        </div>
                        <div class="col-md-3 hidden-lg-down"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('add-style')
    <link rel="stylesheet" href="{{asset('cpanel-asset/css/pages/brand_show.css')}}">
@endpush
