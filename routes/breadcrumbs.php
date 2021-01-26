<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs as Breadcrumbs;
use App\Models\Category;


Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('front.home'), ['urlTitle' => __('pages.index.title')]);
});

Breadcrumbs::register('single_product_category', function ($breadcrumbs, $category, $product) {
    $breadcrumbs->parent('home', route('front.home'));

    $ancestors = $category->getAncestors();

    if (count($ancestors) > 0) {
        foreach ($ancestors as $ancestor) {
            $breadcrumbs->push($ancestor->name, $ancestor->generateUrl());
        }
    }

    $breadcrumbs->push($category->name, $category->generateUrl());

    if (strlen($product->title) > 60) {
        $breadcrumbs->push(substr($product->title, 0, 60) . '...');
    } else {
        $breadcrumbs->push($product->title);
    }
});

Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('home', route('front.home'));

    $ancestors = $category->getAncestors();

    if (count($ancestors) > 0) {
        foreach ($ancestors as $ancestor) {
            $breadcrumbs->push($ancestor->name, $ancestor->generateUrl());
        }
    }

    $breadcrumbs->push($category->name);
});
