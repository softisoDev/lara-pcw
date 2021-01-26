<div class="col-lg-12 col-md-12 text-right">
    <ul id="luckmoshy" class="pagination pull-right"></ul>
</div>

<div class="container-fluid">
    {!! Form::hidden('sourceFile', $sourceFile) !!}
    @foreach(array_chunk($products, $perPage) as $parentIndex => $chunks)
        <div class="row luckmoshy-paginator" id="container-pagnation{{($loop->index+1)}}">

            @foreach($chunks as $childIndex => $product)
                @php $uniqueId = ($parentIndex*$perPage)+($childIndex); @endphp

                <div class="col-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row pb-3">
                                <div class="col-10">
                                    {!! Form::text("products[{$uniqueId}][title]", $product->name, array('class' => 'form-control form-control-sm')) !!}
                                </div>
                                <div class="col-2 text-right">
                                    <input type="checkbox" name="products[{{$uniqueId}}][index]" class="radio-switch" checked data-size="mini"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <!-- CAROUSEL -->
                                @include('cpanel.product_import.includes.carousel', ['dataId' => $uniqueId, 'images' => array_merge($product->primary_images, $product->images)])
                                <!-- End CAROUSEL -->
                                </div>

                                <div class="col-8">

                                    <div class="form-group mb-2">
                                        {!! Form::select("products[{$uniqueId}][category]", array(), null, array('class' => "form-control product-category allCategories")) !!}
                                    </div>

                                    <div class="form-group mb-2">
                                        <strong>Primary category: </strong> {{$product->primaryCategory}}
                                    </div>

                                    <div class="form-group mb-2">
                                        <strong>Brand: </strong> {{$product->brand}}
                                    </div>

                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                @php unset($dataId); @endphp
            @endforeach

        </div>
    @endforeach
</div>

