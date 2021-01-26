<div class="col-lg-12 col-md-12 text-right">
    <span class="pull-right">{{$paginator->links()}}</span>
</div>
{!! Form::hidden('sourceFile', $sourceFile) !!}
@foreach($products['data'] as $k => $product)

    <div class="col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row pb-3">
                    <div class="col-10">
                        {!! Form::text("products[{$k}][title]", $product->name, array('class' => 'form-control form-control-sm')) !!}
                    </div>
                    <div class="col-2 text-right">
{{--                        <input type="checkbox" name="products[{{$k}}][index]" class="radio-switch" checked data-size="mini"/>--}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <!-- CAROUSEL -->
                    @include('cpanel.product_import.includes.carousel', ['dataId' => $k, 'images' => array_merge($product->primary_images, $product->images)])
                    <!-- End CAROUSEL -->
                    </div>

                    <div class="col-8">
                        <div class="form-group mb-2">
{{--                            <span class="pull-right"><a href="javascript:void(0);" data-toggle="modal" data-target="#descriptionModal-{{$k}}">Choose descriptions</a></span>--}}
                            @include('cpanel.product_import.modals.description', ['dataId' => $k,'descriptions' => $product->descriptions])
                        </div>

                        <div class="form-group mb-2">
                            {!! Form::select("products[{$k}][category]", array(), null, array('class' => "form-control product-category allCategories")) !!}
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
@endforeach


