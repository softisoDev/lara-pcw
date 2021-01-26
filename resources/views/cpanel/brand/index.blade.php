@extends('cpanel.layouts.main')

@section('content')

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        {{--  main tab  --}}
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#main" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><i class="fa fa-tags"></i> Brands</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#trash" role="tab"><span class="hidden-sm-up"><i
                                        class="ti-user"></i></span> <span class="hidden-xs-down"><i class="fa fa-trash-o"></i> Trashed</span></a>
                        </li>

                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabcontent-border">

                        <div class="tab-pane active p-10" id="main" role="tabpanel">


                            <div class="table-responsive text-nowrap">
                                <a href="{{addSlash2Url(route('admin.brand.create'))}}" target="_blank" class="btn btn-sm btn-primary  pull-right"><i class="fa fa-plus"></i> Create new brand</a>
                                {{$dataTable->table()}}
                            </div>


                        </div>

                        <div class="tab-pane p-10" id="trash" role="tabpanel">


                            <div class="table-responsive text-nowrap">
                                <a href="{{addSlash2Url(route('admin.category.create'))}}" target="_blank" class="btn btn-sm btn-primary  pull-right"><i class="fa fa-plus"></i> Create new category</a>
                                <table id="trash-table" class="table w-100 display nowrap table-striped table-bordered scroll-horizontal-vertical base-style dtTable">
                                    <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>Deleted at</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>Deleted at</th>
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
    <!-- row -->

@endsection

@push('add-script')
    {!! $dataTable->scripts() !!}
    <script src="{!! asset('cpanel-asset/plugins/tables/js/datatable/action_button_selection.js') !!}"></script>
    <script src="{{asset('cpanel-asset/js/pages/brand_index.js')}}"></script>
@endpush
