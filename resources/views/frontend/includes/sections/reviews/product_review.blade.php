@if($totalReview != 0)
    <article class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{$product->title}} reviews and feedback</h2>
                    <p>{{sprintf(__('pages.products.single.review_info'), $product->title, $totalReview, $product->reviewDetail->average)}}</p>
                    {{-- reviews --}}
                    <div id="review-result-area">
                        @if($totalReview != 0)
                            @foreach($reviews as $review)
                                <div class="row blockquote review-item" itemprop="review" itemscope itemtype="http://schema.org/Review">
                                    <div class="col-md-2 text-center">
                                        <img class="rounded-circle reviewer" src="https://larapcw.com/uploads/image/avatar.png">
                                        <div class="caption">
                                            <small itemprop="name">by
                                                <a href="javascript:void(0)"><span itemprop="author">{{$review->user_name}}</span></a>
                                            </small>
                                        </div>

                                    </div>
                                    <div class="col-md-10">
                                        <h5><span itemprop="name">{{$review->title}}</span></h5>

                                        <div class="d-none" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                            <meta itemprop="worstRating" content="1">
                                            <span itemprop="ratingValue">{{$review->rating}}</span>/
                                            <span itemprop="bestRating">5</span>stars
                                        </div>

                                        @if(strlen($review->text) > 300)
                                            <p class="review-text">{{substr($review->text, 0, 300)}}
                                                <span class="collapse review-text-field" id="description-{{$review->id}}" itemprop="description">
                                                        {{substr($review->text, 300, strlen($review->text))}}
                                                    </span>
                                                <a data-toggle="collapse" onclick="listenReadMore(this)" data-target="#description-{{$review->id}}"> Read more</a>
                                            </p>
                                        @else
                                            <p class="review-text" itemprop="description">{!! $review->text !!}</p>
                                        @endif

                                        <small class="review-date">
                                            <meta itemprop="datePublished" content="{{$review->created_at}}">
                                            {{\Illuminate\Support\Carbon::createFromTimeString($review->created_at)->translatedFormat('F d, Y')}}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>

                    {{-- load more button --}}
                    @if($totalReview > 15)
                        <div id="load-more-area" class="mt-3 text-center">
                            <button id="load-more" onclick="loadMore(this)" data-product-id="{{$product->id}}" class="btn btn-success"> Load more
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </article>
@endif
