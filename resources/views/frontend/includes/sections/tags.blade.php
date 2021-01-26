@if($tags->count() != 0)
    <article class="card">
        <div class="card-body">
            @foreach($tags as $tag)
                <a href="{{addSlash2Url(route('front.tag.show', ['tag' => $tag->slug]))}}"><span class="badge badge-primary">{{implode(' ', explode('-', $tag->slug))}}</span></a>
            @endforeach
        </div>
    </article>
@endif
