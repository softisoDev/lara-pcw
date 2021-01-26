@extends('frontend.layouts.main')

@section('content')
    <!-- ========= MAIN ========= -->
    @include('frontend.includes.sections.main')
    <section class="section-content padding-y">
        <div class="container">
            <div class="row">
                <aside class="col-md-12">
                    @include('frontend.includes.sliders.slick', ['items' => $products])
                </aside>
            </div>
        </div>
    </section>
@endsection
