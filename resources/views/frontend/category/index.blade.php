@extends('frontend.layouts.main')

@section('content')
    <section class="section-pagetop bg">
        <div class="container">
            <h1 class="title-page">{{$category->name}} products</h1> <span>{{$products->total()}} Items found </span>
            {{Breadcrumbs::render('category', $category)}}
        </div>
    </section>

    <section class="padding-top">
        <div class="container">
            <div class="row">
                <main class="col-md-9">
                    @if(!$products->count())
                        <h5>There is no any item in this category</h5>
                    @endif

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
                                                    <div class="my-rating" data-id="{{$product->sp_hash}}"
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
                                                {{config('constants.currency')[$product->cheapestSource->currency] ?? null}} {{$product->cheapestSource->current_price ?? null}}
                                                <span
                                                    class="font-weight-light font-14px">on {{$product->cheapestSource->source}}</span>
                                            </span>
                                                {{--<del class="price-old"> $198</del>--}}
                                            </div> <!-- info-price-detail // -->
                                            <p class="text-success">Free shipping</p>
                                            <br>
                                            <p>
                                                <a href="{{$product->generateUrl()}}"
                                                   class="btn btn-primary btn-block"> Details </a>
                                            </p>
                                        </div> <!-- info-aside.// -->
                                    </aside> <!-- col.// -->
                                </div> <!-- row.// -->
                            </article>
                        @endforeach
                    </div>

                    @if ( $products->lastPage() > 1 )
                        {{ $products->links() }}
                    @endif

                </main>
                @include('frontend.includes.sections.left_sidebar', ['widgets' => array('category' => $category)])
            </div>{{-- row --}}
        </div>{{-- container --}}
    </section>

    @if( $products->currentPage() == 1 )
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <article class="card">
                            <div class="card-body">
                                @if(count($tags) > 0 )
                                    @foreach($tags as $tag)
                                        <a href="{{ addSlash2Url(route('front.tag.show', ['tag' => seoUrl($tag)])) }}">
                                            <span class="badge badge-primary">{{$tag}}</span>
                                        </a>
                                    @endforeach
                                    <hr/>
                                @endif

                                    <h2>{{sprintf(__('pages.category.info_h2'), $category->name)}}</h2>
                                @if(is_null($category->content))
                                    <p>{{sprintf(__('pages.category.info'), $category->name, $products->total(), mt_rand(35, 50), $category->name)}}</p>
                                @else
                                    {!! $category->content !!}
                                @endif

                            </div>
                        </article>
                    </div>

                </div>
            </div>
        </section>
        <br>

        <div class="m-t-20 m-b-20">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @include('frontend.includes.sections.reviews.category_review', ['reviews' => $reviews, 'totalReview' => count($reviews), 'totalProduct' => $products->total(), 'category' => $category])
                    </div>
                </div>
            </div>
        </div>
        <br>
    @endif
@endsection

@push('add-script')
    <script type="application/ld+json">{!! $categoryBreadcrumb !!}</script>
@endpush


@push('add-head')
    @include('frontend.includes.head_pagination', ['pagination' => ['previous' => $products->previousPageUrl(), 'next' => $products->nextPageUrl(), 'current' => $products->url($products->currentPage())]])
@endpush

