<?php

namespace App\Http\Controllers\CPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function load_more(Request $request)
    {
        return true;
    }
}
