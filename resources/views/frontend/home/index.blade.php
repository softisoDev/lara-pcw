@extends('frontend.layouts.main')

@section('content')

    @foreach($categories as $category)
        <section class="section-content">
            <div class="container">
                <header class="section-heading">
                    <a title="{{$category['category']['name']}}" href="{{categoryUrl($category['category'])}}" class="btn btn-outline-primary float-right">See
                        all</a>
                    <h3 class="section-title">{{$category['category']['name']}}</h3>
                </header>

                <div class="row">
                    @foreach($category['products'] as $product)

                        <div class="col-md-3">
                            <div href="{{$product['url']}}" class="card card-product-grid">

                                <a href="{{$product['url']}}"
                                   class="img-wrap"> <img class="img-responsive" src="{{$product['gridImgUrl']}}" alt="{{$product['title']}}">
                                </a>

                                <figcaption class="info-wrap">
                                    <a href="{{$product['url']}}"
                                       class="title">{{$product['title']}}</a>

                                    <div class="price mt-1">{{config('constants.currency')[$product['cheapestSource']->currency] ?? null}} {{$product['cheapestSource']->current_price ?? null}}</div>
                                </figcaption>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    <section class="section-content p-3">
        <div class="container">
            <h1>larapcw - price comparison of products, customer reviews</h1>
            <p>Find out the cheapest deals offered and track the price by comparing the prices of products sold in different stores. View customer review.</p>

            <h2>{{__('pages.index.info_h2')}}</h2>
            <p>{{$info[1]}}</p>

            <h2>{{__('pages.index.review_h2')}}</h2>
            <p>{{$info[2]}}</p>
        </div>
    </section>

@endsection
