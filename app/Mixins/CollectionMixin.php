<?php


namespace App\Mixins;

class CollectionMixin
{
    public function grid()
    {
        return function () {

            return $this->map(function ($item) {

                $item->cheapestSource = new \stdClass();

                $item->cheapestSource->currency = $item->cheapestWithoutSource->currency;
                $item->cheapestSource->current_price = $item->cheapestWithoutSource->current_price;

                //media
                $item->gridImgUrl = $item->gridMainImageUrl;
                $item->url = $item->generateUrl();

                unset($item->gridMainImageUrl);
                unset($item->cheapestWithoutSource);

                return $item;
            });
        };
    }

    public function cgrid()
    {
        return function () {

            return $this->map(function ($item) {

                $item->cheapestSource = new \stdClass();

                $item->cheapestSource->currency = $item->cheapest->currency;
                $item->cheapestSource->current_price = $item->cheapest->current_price;
                $item->cheapestSource->source = $item->cheapest->source->name;

                //review info
                $item->reviewInfo = new \stdClass();
                $item->reviewInfo->aggregate = $item->reviewDetail->aggregate;
                $item->reviewInfo->average = $item->reviewDetail->average;

                //media
                $item->gridImgUrl = $item->gridMainImageUrl;

                unset($item->gridMainImageUrl);
                unset($item->cheapest);
                unset($item->reviewDetail);

                return $item;
            });
        };
    }

    public function similarGrid()
    {
        return function () {

            return $this->map(function ($item) {

                $item->cheapestSource = new \stdClass();

                $item->cheapestSource->currency = $item->cheapest->currency;
                $item->cheapestSource->current_price = $item->cheapest->current_price;
                $item->cheapestSource->source = $item->cheapest->source->name;

                //media
                $item->gridImgUrl = $item->gridMainImageUrl;

                unset($item->gridMainImageUrl);
                unset($item->cheapest);

                return $item;
            });
        };
    }

    public function single()
    {
        return function () {

            return $this->map(function ($item) {

                //main category
                $item->main_category = $item->category[0];

                unset($item->category);

                return $item;
            });
        };
    }
}
