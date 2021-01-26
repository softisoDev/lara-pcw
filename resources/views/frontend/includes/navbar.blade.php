<nav class="navbar navbar-main navbar-expand-lg navbar-light border-bottom">
    <div class="container">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav"
                aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="main_nav">
            <ul class="navbar-nav">

                <li class="nav-item dropdown">
                    <a title="{{__('pages.index.title')}}" class="nav-link" href="{{addSlash2Url(route('front.home'))}}">Home</a>
                </li>

                @for($i=0; $i<4; $i++)
                    <li class="nav-item dropdown">
                        <a title="{{$navbar[$i]['name']}}" class="nav-link" href="{{$navbar[$i]['url']}}">{{$navbar[$i]['name']}}</a>
                    </li>
                @endfor

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"> More</a>
                    <div class="dropdown-menu">
                        @for($i=4; $i<count($navbar); $i++)
                            <a title="{{$navbar[$i]['name']}}" class="dropdown-item" href="{{$navbar[$i]['url']}}">{{$navbar[$i]['name']}}</a>
                         @endfor
                    </div>
                </li>

                {{--@foreach($navbar->skip(0)->take(4) as $nav)
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{$nav['url']}}">{{$nav['name']}}</a>
                    </li>
                @endforeach

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"> More</a>
                    <div class="dropdown-menu">
                        @foreach($navbar->skip(4) as $nav)
                            <a class="dropdown-item" href="{{$nav['url']}}">{{$nav['name']}}</a>
                        @endforeach
                    </div>
                </li>--}}

            </ul>
        </div> <!-- collapse .// -->
    </div> <!-- container .// -->
</nav>
