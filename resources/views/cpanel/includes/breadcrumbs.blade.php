@php $segments = \Illuminate\Support\Facades\Request::segments(); @endphp

<div class="row page-titles">
    <div class="col-md-12 col-121 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
        {{--<ol class="breadcrumb">
            --}}{{--<li class="breadcrumb-item"><a href="javascript:void(0)">{{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render('settings')}}</a></li>--}}{{--
            @foreach($segments as $segment)
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{\DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render($segment)}}</a></li>
            @endforeach
        </ol>--}}
        <ol class="breadcrumb">
            {{--@foreach($segments as $segment)
                @if($segment != 'cpanel')
                    @php $breadcrumb = \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::generate($segment); @endphp
                    <li class="breadcrumb-item"><a href="{{$breadcrumb[0]->url}}">{{$breadcrumb[0]->title}}</a></li>
                @endif
            @endforeach--}}
        </ol>
    </div>
</div>
