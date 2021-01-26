<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="@if(basename(url()->current()) == basename(route('admin.dashboard'))) active @endif"><a
                        class="waves-effect" href="{{addSlash2Url(route('admin.dashboard'))}}"><i
                            class="mdi mdi-gauge"></i>Dashboard</a></li>

                <li class="@if(preg_match('/setting/s', url()->current())) active @endif">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="mdi mdi-laptop-windows"></i><span class="hide-menu">Settings</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">

                        <li class="@if(basename(url()->current()) == basename(route('admin.profile'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.profile'))) active @endif"
                               href="{{addSlash2Url(route('admin.profile'))}}">Profile</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.setting.price.updater'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.setting.price.updater'))) active @endif"
                               href="{{addSlash2Url(route('admin.setting.price.updater'))}}">
                                Price updater
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="@if(preg_match('/product/', url()->current())) active @endif">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="mdi mdi-box-shadow"></i><span class="hide-menu">Product</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">

                        <li class="@if(basename(url()->current()) == basename(route('admin.product.import'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.product.import'))) active @endif"
                               href="{{addSlash2Url(route('admin.product.import'))}}">Import product</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.product.index'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.product.index'))) active @endif"
                               href="{{addSlash2Url(route('admin.product.index'))}}">List product</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.product.create'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.product.create'))) active @endif"
                               href="{{addSlash2Url(route('admin.product.create'))}}">Create product</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.product.source.index'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.product.source.index'))) active @endif"
                               href="{{addSlash2Url(route('admin.product.source.index'))}}">Source domains</a>
                        </li>

                    </ul>
                </li>

                <li class="@if(preg_match('/brand/', url()->current())) active @endif">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="mdi mdi-label"></i><span class="hide-menu">Brand</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">

                        <li class="@if(basename(url()->current()) == basename(route('admin.brand.index'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.brand.index'))) active @endif"
                               href="{{addSlash2Url(route('admin.brand.index'))}}">List brand</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.brand.create'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.brand.create'))) active @endif"
                               href="{{addSlash2Url(route('admin.brand.create'))}}">Create brand</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.brand.order'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.brand.order'))) active @endif"
                               href="{{addSlash2Url(route('admin.brand.order'))}}">Order brand</a>
                        </li>

                    </ul>
                </li>

                <li class="@if(preg_match('/category/', url()->current())) active @endif">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="mdi mdi-file-tree"></i><span class="hide-menu">Category</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">

                        <li class="@if(basename(url()->current()) == basename(route('admin.category.index'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.category.index'))) active @endif"
                               href="{{addSlash2Url(route('admin.category.index'))}}">List categories</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.category.create'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.category.create'))) active @endif"
                               href="{{addSlash2Url(route('admin.category.create'))}}">Create category</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.category.order'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.category.order'))) active @endif"
                               href="{{addSlash2Url(route('admin.category.order'))}}">Order category</a>
                        </li>

                    </ul>
                </li>

                <li class="@if(preg_match('/tag/', url()->current())) active @endif">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="mdi mdi-tag-multiple"></i><span class="hide-menu">Tag</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">

                        <li class="@if(basename(url()->current()) == basename(route('admin.tag.index'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.tag.index'))) active @endif"
                               href="{{addSlash2Url(route('admin.tag.index'))}}">List tag</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.tag.create'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.tag.create'))) active @endif"
                               href="{{addSlash2Url(route('admin.tag.create'))}}">Create tag</a>
                        </li>

                        <li class="@if(basename(url()->current()) == basename(route('admin.tag.create.multi'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.tag.create.multi'))) active @endif"
                               href="{{addSlash2Url(route('admin.tag.create.multi'))}}">
                                Create Multi Tag
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="@if(preg_match('/log/', url()->current())) active @endif">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="mdi mdi-tag-multiple"></i><span class="hide-menu">Logs</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">

                        <li class="@if(basename(url()->current()) == basename(route('admin.log.price.parser'))) active @endif">
                            <a class="@if(basename(url()->current()) == basename(route('admin.log.price.parser'))) active @endif"
                               href="{{addSlash2Url(route('admin.log.price.parser'))}}">Price parsing</a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item-->
        <a href="{{addSlash2Url(route('logout'))}}" data-toggle="tooltip" title="Logout"><i
                class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>
