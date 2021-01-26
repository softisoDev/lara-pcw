<header class="section-header">

    <section class="header-main border-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-4">
                    <a href="{{addSlash2Url(url('/'))}}" class="brand-wrap">
                        <img class="logo" alt="{{__('pages.index.title')}}" src="{{asset('front-asset/images/logo.png')}}">
                    </a> <!-- brand-wrap.// -->
                </div>
                <div class="col-lg-6 col-sm-12 col-md-8 offset-lg-4">
                    <form action="{{addSlash2Url(route('front.search'))}}" method="get" class="search">
                        <div class="input-group w-100">
                            <input type="text" name="keyword" autocomplete="off" class="form-control" placeholder="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        @error('keyword')<small class="text-red font-weight-bold">{{$message}}</small>@enderror
                    </form> <!-- search-wrap .end// -->
                </div> <!-- col.// -->
                <?php /*
                <div class="col-lg-4 col-sm-6 col-12 hidden">
                    <div class="widgets-wrap float-md-right">
                        <div class="widget-header  mr-3">
                            <a href="#" class="icon icon-sm rounded-circle border"><i class="fa fa-shopping-cart"></i></a>
                            <span class="badge badge-pill badge-danger notify">0</span>
                        </div>
                        <div class="widget-header icontext">
                            <a href="#" class="icon icon-sm rounded-circle border"><i class="fa fa-user"></i></a>
                            <div class="text">
                                <span class="text-muted">Welcome!</span>
                                <div>
                                    <a href="#">Sign in</a> |
                                    <a href="#"> Register</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>*/ ?>
            </div> <!-- row.// -->
        </div> <!-- container.// -->
    </section> <!-- header-main .// -->
</header> <!-- section-header.// -->

