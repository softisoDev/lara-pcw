@foreach($categories->chunk(1) as $chunk)
    <section class="section-content">
        <div class="container">
        @foreach($chunk as $category)
            <!-- header -->
                <header class="section-heading">
                    <a href="{{$category['category']->generateUrl()}}" class="btn btn-outline-primary float-right">See
                        all</a>
                    <h3 class="section-title">{{$category['category']->name}}</h3>
                </header>
                <div class="row">
                    @foreach($category['products'] as $product)

                        <div class="col-md-3">
                            <div href="{{$product->generateUrl()}}" class="card card-product-grid">

                                <a href="{{$product->generateUrl()}}"
                                   class="img-wrap"> <img class="img-responsive" src="{{$product->media->thumb}}">
                                </a>

                                <figcaption class="info-wrap">
                                    <a href="{{$product->generateUrl()}}" class="title">{{$product->title}}</a>

                                    <?php /* <div class="rating-wrap input-group">
                                        <div class="my-rating" data-id="{{$product->id}}" data-rating="{{calculateAverageRating($product->review)}}"></div>
                                        <div class="label-rating mb-2">{{calculateAverageRating($product->review)}}/5</div>
                                    </div>*/ ?>
                                    <div class="price mt-1">{{config('constants.currency')[$product->cheapest->currency]}} {{$product->cheapest->current_price}}</div>
                                </figcaption>
                            </div>
                        </div>

                    @endforeach
                </div>

            @endforeach
        </div>
    </section>
@endforeach
