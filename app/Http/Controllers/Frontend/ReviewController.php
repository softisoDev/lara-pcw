<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends MainController
{

    public function loadMore(Request $request, $productId)
    {
        if ($request->ajax()) {

            $reviews = Review::loadMore($productId, $request->post('skip'));

            return view("{$this->viewFolder}.includes.sections.reviews.only_reviews")
                ->with([
                    'reviews' => $reviews,
                ])->render();
        }
    }

}
