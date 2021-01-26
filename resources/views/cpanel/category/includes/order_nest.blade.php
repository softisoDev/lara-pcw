<li class="dd-item dd3-item" data-id="{{$child->id}}" data-title="{{$child->name}}">
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content"> {{$child->name}}</div>
</li>
@if(!is_null($child->children))
    <ol class="dd-list">
        @foreach($child->children as $child)
            @include('cpanel.category.includes.order_nest', $child)
        @endforeach
    </ol>
@endif
