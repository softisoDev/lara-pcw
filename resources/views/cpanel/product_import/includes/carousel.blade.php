<div class="carousel">

    <div class="carousel-inner">

        <div class="carousel-item active">
            @if(!empty($images))
                <img src="{{$images[0]}}" class="d-block w-100 product-import-carousel">
            @endif
        </div>

    </div>
</div>

<div class="text-center">
   {{-- <a href="javascript:void(0);" onclick="initImagePickerOnModal(this)" data-toggle="modal"
       data-imgpicker-id="imgselect-{{$dataId}}" data-target="#imagesModal-{{$dataId}}">Show Images</a>--}}
    @include('cpanel.product_import.modals.images', ['dataId' => $dataId,'images' => $images])
</div>
