@if(count($products) > 0)
    @foreach($products as $product)
        <div class="col-md-3">

            <div href="{{$product->generateUrl()}}" class="card card-product-grid">

                <a href="{{$product->generateUrl()}}" class="img-wrap">
                    <img class="img-responsive" src="{{$product->gridImgUrl}}" alt="{{$product->title}}">
                </a>

                <figcaption class="info-wrap">
                    <a href="{{$product->generateUrl()}}" class="title">{{$product->title}}</a>
                    <div class="price">{{config('constants.currency')[$product->cheapestSource->currency]}} {{$product->cheapestSource->current_price}}</div>
                </figcaption>
            </div>

        </div>
    @endforeach
@endif


