<!-- ============== COMPONENT SLIDER ITEMS SLICK  ============= -->
<h4>Best seller</h4>
<div class="slider-items-slick row" data-slick='{"slidesToShow": 4, "slidesToScroll": 1}'>
    @foreach($items as $item)
        <div class="item-slide p-2">
            <figure class="card card-product-grid mb-0">
                <div class="img-wrap">
                    <span class="badge badge-danger"> NEW </span>
                    <img src="{{$item->getMainImageUrl('medium-thumb')}}">
                </div>
                <figcaption class="info-wrap text-center">
                    <h6 class="title text-truncate"><a href="{{addSlash2Url(url("products/{$item->sp_hash}-".seoUrl($item->title)))}}">{{$item->title}}</a></h6>
                </figcaption>
            </figure> <!-- card // -->
        </div>
    @endforeach
</div>






