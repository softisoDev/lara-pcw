<aside class="col-md-3">
    @foreach($widgets as $widget)
        @include('frontend.includes.widgets.'.array_search($widget, $widgets), ['data' => $widget])
    @endforeach
</aside>
