@extends('frontend.layouts.main')

@section('content')
    {{-- ========================= SECTION PAGETOP ========================= --}}
    <section class="section-pagetop bg">
        <div class="container">
            <h1 class="title-page">Result for "{{$tag->slug}}"</h1> <span>{{$products->total()}} Items found </span>
        </div> <!-- container //  -->
    </section>
    {{-- ========================= SECTION INTRO END// ========================= --}}

    {{-- ========================= SECTION CONTENT ========================= --}}
    <section class="section-content padding-y">
        <div class="container">
            <div class="row">
                <main class="col-md-9">
                    {{-- products list --}}
                    <div class="prod-list">
                        @foreach($products as $product)

                            <article class="card card-product-list">
                                <div class="row no-gutters">
                                    <aside class="col-md-3">
                                        <a href="{{$product->generateUrl()}}"
                                           class="img-wrap">
                                            {{--<span class="badge badge-danger"> NEW </span>--}}
                                            <img src="{{$product->gridImgUrl}}" alt="{{$product->title}}">
                                        </a>
                                    </aside> <!-- col.// -->
                                    <div class="col-md-6">
                                        <div class="info-main">
                                            <a href="{{$product->generateUrl()}}"
                                               class="h5 title">
                                                {{$product->title}}
                                            </a>
                                            @if($product->reviewInfo->aggregate > 0)
                                                <div class="mb-3 input-group">
                                                    <div class="my-rating" data-id="{{$product->id}}"
                                                         data-rating="{{$product->reviewInfo->average}}"></div>
                                                    <div
                                                        class="label-rating mb-2">{{$product->reviewInfo->average}}
                                                        /5
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- rating-wrap.// --}}

                                            <p>{{substr($product->description, 0, 150)}}...</p>
                                        </div> <!-- info-main.// -->
                                    </div> <!-- col.// -->
                                    <aside class="col-sm-3">
                                        <div class="info-aside">
                                            <div class="price-wrap">
                                            <span class="price h5">
                                                {{config('constants.currency')[$product->cheapestSource->currency]}} {{$product->cheapestSource->current_price}}
                                                <span
                                                    class="font-weight-light font-14px">on {{$product->cheapestSource->source}}</span>
                                            </span>
                                                {{--<del class="price-old"> $198</del>--}}
                                            </div> <!-- info-price-detail // -->
                                            <p class="text-success">Free shipping</p>
                                            <br>
                                            <p>
                                                <a href="{{$product->generateUrl()}}" class="btn btn-primary btn-block"> Details </a>
                                            </p>
                                        </div> <!-- info-aside.// -->
                                    </aside> <!-- col.// -->
                                </div> <!-- row.// -->
                            </article>
                        @endforeach
                    </div>

                    {{-- pagination --}}
                    @if ( $products->lastPage() > 1 )
                        {{ $products->links() }}
                    @endif
                </main> {{-- col.// --}}
            </div>
        </div> {{-- container.// --}}
    </section>

    @if($products->currentPage() == 1)
        @if ( !is_null($tag->content) )
            <div class="bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 bg">
                            {!! $tag->content !!}
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        @endif
        <div class="m-t-20 m-b-20">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @include('frontend.includes.sections.reviews.tag_review', ['reviews' => $reviews, 'totalReview' => count($reviews)])
                    </div>
                </div>
            </div>
        </div>
        <br><br>
    @endif
@endsection

@push('add-head')
    @include('frontend.includes.head_pagination', ['pagination' => ['previous' => $products->previousPageUrl(), 'next' => $products->nextPageUrl(), 'current' => $products->url($products->currentPage())]])
@endpush
