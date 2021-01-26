@foreach($categories as $category)
    @if(!$loop->last)
        @include('cpanel.category.includes.sub_category', ['category' => $category, 'selected' => $categories[$loop->index+1]->id])
    @endif
@endforeach
