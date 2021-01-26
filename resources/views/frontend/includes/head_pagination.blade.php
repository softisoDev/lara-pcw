@isset($pagination)

    @if(!is_null($pagination['previous']))<link rel="prev" href="{{preg_replace('/\?page=[1]$/', '', $pagination['previous'])}}"/>@endif

    <link rel="canonical" href="{{preg_replace('/\?page=[1]$/', '', $pagination['current'])}}"/>

    @if(!is_null($pagination['next']))<link rel="next" href="{{$pagination['next']}}"/>@endif

@endisset
{{--preg_replace('/\?page=[1]$/', '/', $url)--}}

