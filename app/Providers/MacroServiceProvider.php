<?php

namespace App\Providers;

use App\Mixins\BuilderMixin;
use App\Mixins\CollectionMixin;
use App\Mixins\ScoutBuilderMixin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use ReflectionException as ReflectionExceptionAlias;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws ReflectionExceptionAlias
     */
    public function boot()
    {
        Builder::mixin(new BuilderMixin);
        Collection::mixin(new CollectionMixin);
        \Laravel\Scout\Builder::mixin(new ScoutBuilderMixin);
    }
}
