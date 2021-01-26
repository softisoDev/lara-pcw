<?php

namespace App\Http\Controllers\CPanel;

use App\Models\ProductVariation;

class ProductVariationController extends MainController
{

    public $subViewFolder;

    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = 'product_variation';
    }

    public function edit(ProductVariation $variation)
    {

    }

}
