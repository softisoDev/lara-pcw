@if($totalReview != 0)
    <article class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h4>Reviews</h4>

                    {{-- reviews --}}
                    <div id="review-result-area">
                        @if($totalReview != 0)
                            @foreach($reviews as $review)
                                <div class="row blockquote review-item">
                                    <div class="col-md-3 text-center">
                                        <img class="rounded-circle reviewer"
                                             src="https://larapcw.com/uploads/image/avatar.png">
                                        <div class="caption">
                                            <small>by <a href="javascript:void(0)">{{$review->user_name}}</a></small>
                                        </div>

                                    </div>
                                    <div class="col-md-9">
                                        <h5>{{$review->title}}</h5>
                                        <p class="review-text">{!! $review->text !!}</p>
                                        <small class="review-date">{{$review->published_at}}</small>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </article>
@endif
