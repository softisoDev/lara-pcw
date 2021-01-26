<?php

Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes([
    'register' => false,
]);



/* Dashboard routes start */
Route::get('cpanel', 'CPanel\DashboardController@index')->name('admin.dashboard');

/* Front panel */
Route::group(['as' => 'front.'], function () {
    Route::get('/', 'Frontend\HomeController@index')->name('home');
    Route::get('/test', 'Frontend\HomeController@test')->name('home.test');

    //Search
    Route::get('search', 'Frontend\SearchController@index')->name('search');
    Route::get('search/{query}', 'Frontend\SearchController@doSearch')->name('search.do');

    Route::get('r', 'Frontend\ProductController@random')->name('product.random');

    //category product
    Route::get('{slugCategory}-{category}', 'Frontend\CategoryController@index')
        ->name('category.show')
        ->where('category', '[0-9]+')
        ->where('slugCategory', '[\w-]+');

    //single product
    Route::get('products/{product}', 'Frontend\ProductController@show')
        ->name('product.show')
        ->where('product', '[a-zA-Z0-9]+');

    Route::post('products/{product}/{category}/similar', 'Frontend\ProductController@similar')->name('product.similar');

    // tag
    Route::get('{tag}', 'Frontend\TagController@index')->name('tag.show');

    //Product review
    Route::post('review/load-more/{productId}', 'Frontend\ReviewController@loadMore')->name('review.load.more');

});



Route::group(['prefix' => 'cpanel', 'as' => 'admin.', 'middleware' => ['auth']], function () {

    // Dashboard
    Route::get('/', 'CPanel\DashboardController@index')->name('dashboard');
    Route::get('dashboard', 'CPanel\DashboardController@index')->name('dashboard');

    //Product Import
    Route::post('product/import/search', 'CPanel\ProductImportController@search')->name('product.import.search');
    Route::post('product/import/search/save', 'CPanel\ProductImportController@saveSearchPerm')->name('product.import.search.save');
    Route::post('product/import/save', 'CPanel\ProductImportController@saveProduct')->name('product.import.save');
    Route::post('product/import/make', 'CPanel\ProductImportController@makeTemplate')->name('product.import.make');
    Route::get('product/import', 'CPanel\ProductImportController@index')->name('product.import');
    Route::get('product/import/test', 'CPanel\ProductImportController@test')->name('product.import.test');

    //Product
    Route::post('product/datatable', 'CPanel\ProductController@getDataTable')->name('product.datatable');
    Route::get('product/{product}/images', 'CPanel\ProductController@singleProductImages')->name('product.images');
    Route::post('product/main-image-setter/{product}/{media}', 'CPanel\ProductController@mainImageSetter')->name('product.main.image.setter');
    Route::post('product/image/upload/{product}', 'CPanel\ProductController@uploadImage')->name('product.image.upload');
    Route::post('product/restore/{id}', 'CPanel\ProductController@restoreTrash')->name('product.restore');
    Route::post('product/boolean-setter/{id}', 'CPanel\ProductController@trueFalseSetter')->name('product.boolean.setter');
    Route::post('product/datatable/trashed', 'CPanel\ProductController@getTrashedDataTable')->name('product.datatable.trashed');
    Route::delete('product/destroy/permanently/{id}', 'CPanel\ProductController@destroyTrash')->name('product.datatable.destroy.permanently');
    Route::get('product/order', 'CPanel\ProductController@order')->name('product.order');
    Route::post('product/{product}/variation', 'CPanel\ProductController@getVariationDataTable')->name('product.variation.list');
    Route::resource('product', 'CPanel\ProductController')->names([
        'index'  => 'product.index',
        'store'  => 'product.store',
        'update' => 'product.update',
        'delete' => 'product.delete',
        'show'   => 'product.show',
    ]);



    //sources (domains)
    Route::get('product/source/list', 'CPanel\SourceController@index')->name('product.source.index');
    Route::post('product/source/{id}/status', 'CPanel\SourceController@trueFalseSetter')->name('product.source.status');
    Route::post('product/source/media', 'CPanel\SourceController@uploadMedia')->name('product.source.media.upload');

    //Generate slug
    Route::post('cpanel/generate/slug', 'CPanel\MainController@generateSlug')->name('generate.slug');

    // Profile setting
    Route::resource('setting/profile', 'CPanel\UserProfileController')->names([
        'index'  => 'profile',
        'store'  => 'profile.store',
        'update' => 'profile.update',
        'delete' => 'profile.delete',
        'show'   => 'profile.show'
    ]);

    Route::put('setting/profile/password/{id}', 'CPanel\UserProfileController@update_password')->name('profile.password.update');
    Route::get('setting/price-updater', 'CPanel\SettingController@showPriceUpdater')->name('setting.price.updater');
    Route::post('setting/price-updater', 'CPanel\SettingController@priceUpdaterRun')->name('setting.price.updater.run');

    //Brand
    Route::post('brand/datatable', 'CPanel\BrandController@getDataTable')->name('brand.datatable');
    Route::post('brand/restore/{id}', 'CPanel\BrandController@restoreTrash')->name('brand.restore');
    Route::post('brand/datatable/trashed', 'CPanel\BrandController@getTrashedDataTable')->name('brand.datatable.trashed');
    Route::delete('brand/destroy/permanently/{id}', 'CPanel\BrandController@destroyTrash')->name('brand.datatable.destroy.permanently');
    Route::get('brand/order', 'CPanel\BrandController@order')->name('brand.order');
    Route::post('brand/order/update', 'CPanel\BrandController@setOrder')->name('brand.order.update');
    Route::resource('brand', 'CPanel\BrandController')->names([
        'index'  => 'brand.index',
        'store'  => 'brand.store',
        'update' => 'brand.update',
        'delete' => 'brand.delete',
        'show'   => 'brand.show',
    ]);

    //Category
    Route::post('category/datatable', 'CPanel\CategoryController@getDataTable')->name('category.datatable');
    Route::post('category/select2', 'CPanel\CategoryController@select2Remote')->name('category.select2');
    Route::post('category/restore/{id}', 'CPanel\CategoryController@restoreTrash')->name('category.restore');
    Route::post('category/subcategory/{category}', 'CPanel\CategoryController@getSubcategory')->name('category.subcategory');
    Route::post('category/boolean-setter/{id}', 'CPanel\CategoryController@trueFalseSetter')->name('category.boolean.setter');
    Route::post('category/datatable/trashed', 'CPanel\CategoryController@getTrashedDataTable')->name('category.datatable.trashed');
    Route::delete('category/destroy/permanently/{id}', 'CPanel\CategoryController@destroyTrash')->name('category.datatable.destroy.permanently');
    Route::get('category/order', 'CPanel\CategoryController@order')->name('category.order');
    Route::post('category/order/update', 'CPanel\CategoryController@setOrder')->name('category.order.update');
    Route::resource('category', 'CPanel\CategoryController')->names([
        'index'  => 'category.index',
        'store'  => 'category.store',
        'update' => 'category.update',
        'delete' => 'category.delete',
        'show'   => 'category.show',
    ]);

    //Media
    Route::delete('media/{media}', 'CPanel\MainController@deleteMediaAjax')->name('media.delete');

    //Tags
    Route::post('tag/datatable', 'CPanel\TagController@getDataTable')->name('tag.datatable');
    Route::get('tag/find', 'CPanel\TagController@fetchTags')->name('tag.find');
    Route::post('tag/restore/{id}', 'CPanel\TagController@restoreTrash')->name('tag.restore');
    Route::post('tag/datatable/trashed', 'CPanel\TagController@getTrashedDataTable')->name('tag.datatable.trashed');
    Route::delete('tag/destroy/permanently/{id}', 'CPanel\TagController@destroyTrash')->name('tag.datatable.destroy.permanently');
    Route::post('tag/{tag}/option/update', 'CPanel\TagController@updateOption')->name('tag.option.update');
    Route::post('tag/trueFalseSetter/{tag}', 'CPanel\TagController@trueFalseSetter')->name('tag.boolean.setter');
    Route::post('tag/multi-store', 'CPanel\TagController@multiStore')->name('tag.multi.store');
    Route::get('tag/create-multi', 'CPanel\TagController@createMulti')->name('tag.create.multi');

    Route::resource('tag', 'CPanel\TagController')->names([
        'index'  => 'tag.index',
        'store'  => 'tag.store',
        'update' => 'tag.update',
        'delete' => 'tag.delete',
        'show'   => 'tag.show',
    ]);

    //Log
    //Log
    Route::get('log/price-parser', 'CPanel\LogController@showPriceParser')->name('log.price.parser');
    Route::post('log/price-parser-data', 'CPanel\LogController@getPriceParserData')->name('log.price.parser.data');

});


