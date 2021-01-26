@extends('frontend.layouts.main')

@section('content')
    <section class="section-content pt-3 bg">
        <div class="container">
            <div class="card">
                <div class="row no-gutters">
                    <aside class="col-md-12 p-3 border-right">
                        {{Breadcrumbs::render('single_product_category', $category, $product)}}
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <section class="section-content pt-3 pb-3 bg">
        <div class="container">
            <div class="card">
                <div class="row no-gutters">

                    <aside class="col-sm-3 border-right">
                        @if (!empty($product->getFirstMediaUrl('image')))
                            <img onclick="gallery()" class="img-responsive w-100" src="{{ $product->getFirstMediaUrl('image') }}" alt="{{$product->title}}">
                        @else
                            <img class="img-responsive  w-100" src="{{config('constants.image.no_image')}}"
                                 alt="{{$product->title}}">
                        @endif
                    </aside>

                    <main class="col-sm-9 p-3">
                        <h1 class="title">{{$product->title}}</h1>
                        @if($product->reviewDetail->aggregate > 0)
                            <div class="col input-group" itemprop="aggregateRating" itemscope
                                 itemtype="http://schema.org/AggregateRating">

                                <div class="my-rating" data-id="{{$product->id}}"
                                     data-rating="{{$product->reviewDetail->average}}"></div>
                                <div class="label-rating mt-1"
                                     itemprop="ratingValue">{{$product->reviewDetail->average}}/5
                                </div>

                                <small class="label-rating text-muted mt-2"
                                       itemprop="reviewCount">{{$product->reviewDetail->aggregate}}
                                    reviews</small>
                            </div>
                        @endif
                        <div class="row mt-3 align-items-center">
                            <div class="col">
                                    <span class="price h4">{{config('constants.currency')[$product->variations->first()->currency]}} {{$product->variations->first()->current_price}}</span>
                                {{--<span> &nbsp;&nbsp; on <a rel="sponsored nofollow" href="{{$product->variations->first()->source->generateUrl()}}">{{$product->variations->first()->source->name}}</a> </span>--}}
                            </div>
                            {{--<div class="col text-right">
                                <a rel="sponsored nofollow" rel="sponsored nofollow"
                                   href="{{$product->variations->first()->source_url}}" class="btn  btn-primary">
                                    <span class="text-hidden">Go to product</span> <i
                                        class="fas fa-arrow-circle-right"></i> </a>
                            </div>--}}
                        </div>
                    </main>.


                    <main class="col-sm-12">
                        <article class="content-body">

                            <div class="row mt-3 align-items-center">
                                <div class="pt-3">
                                    <p>{{sprintf(__('pages.products.single.prices'), $product->title, count($product->variations))}}</p>

                                    <p>{{
                                    sprintf(__('pages.products.single.prices2'),
                                    config('constants.currency')[$product->variations->first()->currency] . $product->variations->first()->current_price,
                                    config('constants.currency')[$product->variations->last()->currency] . $product->variations->last()->current_price)
                                    }}

                                    <p>{{
                                      sprintf(__('pages.products.single.best_price'),
                                      $product->title,
                                      config('constants.currency')[$product->variations->first()->currency] . $product->variations->first()->current_price,
                                      $product->variations->first()->source->name)
                                    }}</p>

                                    <p>{{sprintf(__('pages.products.single.delivery'), $product->title)}}</p>

                                </div>
                            </div>
                        </article>
                    </main>

                </div>
            </div>
            <br/>

            {{-- Variations --}}
            @if( count($product->variations) )
                <article class="card">
                    <div class="card-body">
                        <h2>{{$product->title}} price comparison</h2>
                        <table class="table table-borderless table-shopping-cart">
                            <thead class="text-muted">
                            <tr class="small text-uppercase">
                                <th>Other sources</th>
                                <th width="120">Price</th>
                                <th class="text-right" width="200"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->variations as $variation)
                                <tr>
                                    <td>
                                        <figure class="itemside align-items-center">
                                            <div class="aside"><img src="{{sourceMediaUrl($variation->source->name)}}"
                                                                    class="img-source-domain"></div>
                                            <figcaption class="info">
                                                <a href="{{$variation->source_url}}"
                                                   class="title text-dark">{{substr($variation->title, 0, 60)}}</a>
                                                <p class="text-muted small">
                                                    <strong>Source</strong>: {{$variation->source->name}}
                                                    <br>
                                                    <strong>Brand: </strong> {{$product->brand->name ?? ""}}
                                                    <br>
                                                </p>
                                            </figcaption>
                                        </figure>
                                    </td>
                                    <td>
                                        <div class="price-wrap">
                                            <var
                                                class="price">{{config('constants.currency')[$variation->currency]}} {{$variation->current_price}}</var>
                                        </div> <!-- price-wrap .// -->
                                    </td>
                                    <td class="text-right">
                                        <a rel="sponsored nofollow" href="{{$variation->source_url}}"
                                           class="btn  btn-primary">
                                            <span class="text-hidden">Go to product</span> <i
                                                class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <p>{{sprintf(__('pages.products.single.variations'), $product->title)}}</p>
                    </div>
                </article>
                <br/>
            @endif

            {{-- Product Tags --}}
            @if(count($product->tag) != 0)
                <article class="card">
                    <div class="card-body">
                        @foreach($product->tag as $tag)
                            <a href="{{addSlash2Url(route('front.tag.show', ['tag' => seoUrl($tag->slug)]))}}"><span
                                    class="badge badge-primary">{{$tag->slug}}</span></a>
                        @endforeach
                    </div>
                </article>
                <br/>
            @endif

            {{--  Description  --}}
            @if(!is_null($product->description) || !empty($product->description))
                <article class="card">
                    <div class="card-body">
                        <div class="row">
                            <aside class="col-md-12 col-lg-12">
                                <h2>{{$product->title}} Specifications</h2>
                                {!! $product->description !!}
                            </aside>
                        </div>
                    </div>
                </article>
                <br/>
            @endif

            {{--  Features  --}}
            @if( $product->features )
                @if(!is_null($features) || !empty($features))
                    <article class="card">
                        <div class="card-body">
                            <div class="row">
                                <aside class="col-md-12 col-lg-12">
                                    <h2>{{$product->title}} Features</h2>
                                    <ul class="list-check">
                                        @foreach($features as $feature)
                                            <li>{{$feature}}</li>
                                        @endforeach
                                    </ul>
                                </aside>
                            </div>
                        </div>
                    </article>
                @endif
                <br/>
            @endif

            {{-- reviews --}}
            @include('frontend.includes.sections.reviews.product_review', ['reviews' => $product->review, 'product' => $product, 'totalReview' => $product->reviewDetail->aggregate])

            <div id="loading-div" class="text-center d-none">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Similar products -->
            <section class="section-content d-none" id="similar-product-section">
                <div class="container">
                    <header class="section-heading">
                        <h3 class="section-title">Similar Products</h3>
                    </header>

                    <input type="hidden" id="product-id" value="{{$product->id}}">
                    <input type="hidden" id="category-id" value="{{$category->id}}">
                    <div class="row" id="similar-products"></div>
                </div>
            </section>

        </div>
    </section>

@endsection

@push('add-script')
    <script type="application/ld+json">{!! $schema !!}</script>
    <script type="application/ld+json">{!! $productBreadcrumb !!}</script>
@endpush
