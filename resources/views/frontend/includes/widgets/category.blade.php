@php $categories = $data->sideBarList(); @endphp

@if(!empty($categories))
    <div class="card">
        <article class="filter-group">
            <header class="card-header">
                <a href="#" data-toggle="collapse" data-target="#collapse_1" aria-expanded="true"
                   class="">
                    <i class="icon-control fa fa-chevron-down"></i>
                    <h6 class="title">Related categories</h6>
                </a>
            </header>
            <div class="filter-content collapse show" id="collapse_1" style="">
                <div class="card-body">
                    <ul class="list-menu">
                        @foreach($categories as $category)
                            <li><a href="{{categoryUrl($category)}}">{{$category['name']}} </a></li>
                        @endforeach
                    </ul>
                </div> <!-- card-body.// -->
            </div>
        </article> <!-- filter-group  .// -->
    </div>
@endif

