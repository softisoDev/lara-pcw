<?php


namespace App\Libraries\Datafiniti;


class DataParser
{

    public $data;

    public $result = [];

    protected $returnJson;

    protected $price = [];

    protected $codes = [];

    protected $reviews = [];

    protected $variationId;

    protected $name;

    protected $brand;

    protected $primaryImages;

    protected $images;

    protected $primaryCategory;

    protected $categories;

    protected $url;

    protected $color;

    protected $size;

    protected $dimensions;

    protected $weight;

    protected $condition;

    protected $merchant;

    protected $manufacturer;

    protected $manufacturerNumber;

    protected $availability;

    protected $features = [];

    protected $description = [];

    protected $descriptions = [];


    /**
     * DataParser constructor.
     * @param $data
     * @param bool $returnJson
     */
    public function __construct($data, $returnJson = false)
    {
        $this->data = $data;
        $this->returnJson = $returnJson;
    }

    /**
     * @return array
     */
    public function parseMultiData()
    {
        $this->result['number_data'] = get_property($this->data, 'num_found');

        if (is_null(get_property($this->data, 'records')))
            return ['success' => false];

        self::parseRecord($this->data->records);

        $this->result['success'] = true;

        if ($this->returnJson)
            return json_encode($this->result, JSON_PRETTY_PRINT);


        return $this->result;
    }

    /**
     * @param $records
     */
    protected function parseRecord($records): void
    {
        foreach ($records as $record) {

            if (is_null(get_property($record, 'prices')) || !is_array($record->prices))
                continue;

            self::getSingleProperties($record);

            // get codes
            self::parseCodes($record);

            $this->result['records'][] = [
                'codes'           => $this->codes,
                'name'            => $this->name,
                'brand'           => $this->brand,
                'manufacturer'    => $this->manufacturer,
                'descriptions'    => $this->descriptions,
                'reviews'         => $this->reviews,
                'features'        => $this->features,
                'weight'          => preg_replace('/\s+/', ' ', $this->weight),
                'dimension'       => $this->dimensions,
                'images'          => $this->images,
                'primary_images'  => $this->primaryImages,
                'primaryCategory' => $this->primaryCategory,
                'categories'      => $this->categories,
                'variations'      => self::parseDataByPrice($record->prices),
            ];

        }
    }

    /**
     * @param $record
     */
    protected function getSingleProperties($record): void
    {
        // get data name
        $this->name = $record->name;

        //get brand
        $this->brand = get_property($record, 'brand');

        //get dimensions
        $this->dimensions = get_property($record, 'dimensions');

        //get weight
        $this->weight = get_property($record, 'weight');

        // get primary image
        $this->primaryImages = (!is_null(get_property($record, 'primaryImageURLs'))) ? get_property($record, 'primaryImageURLs') : [];

        // get manufacturer
        $this->manufacturer = get_property($record, 'manufacturer');

        // get manufacturerNumber
        $this->manufacturerNumber = get_property($record, 'manufacturerNumber');

        // get features
        $this->features = !is_null(get_property($record, 'features')) ? self::parseFeatures($record->features) : null;

        // get all images
        $this->images = (!is_null(get_property($record, 'imageURLs'))) ? get_property($record, 'imageURLs') : [];

        // get primary category
        $this->primaryCategory = !is_null(get_property($record, 'primaryCategories')) ? $record->primaryCategories[0] : null;

        // get categories
        $this->categories = get_property($record, 'categories');

        //get reviews
        $this->reviews = !is_null(get_property($record, 'reviews')) ? self::parseReviews(get_property($record, 'reviews')) : null;

        // get descriptions
        $this->descriptions = !is_null(get_property($record, 'descriptions')) ? self::parseDescription($record->descriptions) : null;

    }

    /**
     * @param $data
     * @return array
     */
    protected function parseDataByPrice($data): array
    {
        $result = [];

        $this->variationId = uniqid();

        foreach ($data as $datum) {

            $priceSource = get_property($datum, 'sourceURLs');

            if (!is_array($priceSource))
                continue;

            self::parsePrice($datum);

            $result[] = self::parsePriceSource($priceSource);
        }

        return $result;
    }

    /**
     * @param $data
     * @return array
     */
    protected function parsePriceSource($data): array
    {
        $result = [];
        foreach ($data as $source) {

            $result = [
                'condition'    => $this->condition,
                'merchant'     => $this->merchant,
                'color'        => $this->color,
                'size'         => $this->size,
                'availability' => $this->availability,
                'price'        => $this->price,
                'url'          => $source,
            ];

        }
        return $result;
    }

    /**
     * @param $data
     */
    protected function parseCodes($data): void
    {
        $this->codes = [
            'asin'  => get_property($data, 'asins'),
            'upc'   => get_property($data, 'upc'),
            'upce'  => get_property($data, 'upce'),
            'upca'  => get_property($data, 'upca'),
            'ean'   => get_property($data, 'ean'),
            'ean8'  => get_property($data, 'ean8'),
            'ean13' => get_property($data, 'ean13'),
            'vin'   => get_property($data, 'vin'),
            'gtins' => get_property($data, 'gtins'),
            'isbn'  => get_property($data, 'isbn'),
        ];
    }

    /**
     * @param $data
     */
    protected function parsePrice($data): void
    {
        $this->price['max'] = str_replace(',', '', number_format(get_property($data, 'amountMax'), 2));
        $this->price['min'] = str_replace(',', '', number_format(get_property($data, 'amountMin'), 2));
        $this->price['current'] = !is_null(get_property($data, 'current')) ? str_replace(',', '', number_format(get_property($data, 'current'), 2)) : str_replace(',', '', number_format(get_property($data, 'amountMin'), 2));
        $this->price['currency'] = get_property($data, 'currency');

        $this->availability = get_property($data, 'availability');
        $this->condition = get_property($data, 'condition');
        $this->merchant = get_property($data, 'merchant');
        $this->color = get_property($data, 'color');
        $this->size = get_property($data, 'size');
    }

    /**
     * @param $descriptions
     * @return array
     */
    protected function parseDescription($descriptions): array
    {
        $result = [];

        foreach ($descriptions as $description) {
            $source = get_property($description, 'sourceURLs');
            $content = get_property($description, 'value');

            $result[] = [
                'content' => trim($content),
                'source'  => !is_null($source) ? $source[0] : null,
                'length'  => strlen(trim($content)),
            ];
        }

        usort($result, function ($a, $b) {
            return $b['length'] <=> $a['length'];
        });

        return $result;
    }

    /**
     * @param $reviews
     * @return array
     */
    protected function parseReviews($reviews): array
    {
        $result = [];
        foreach ($reviews as $review) {

            $source = get_property($review, 'sourceURLs');
            $dateTime = !is_null(get_property($review, 'date')) ? parseDate($review->date) : null;

            $result[] = [
                'title'        => get_property($review, 'title'),
                'text'         => get_property($review, 'text'),
                'username'     => get_property($review, 'username'),
                'published_at' => !is_null($dateTime) ? $dateTime['date'] : null,
                'source'       => !is_null($source) ? $source[0] : null,
                'rating'       => get_property($review, 'rating'),
            ];
        }

        return $result;
    }

    /**
     * @param $features
     * @return array
     */
    protected function parseFeatures($features): array
    {
        $result = [];

        foreach ($features as $feature) {
            $result[] = [
                'key'   => get_property($feature, 'key'),
                'value' => get_property($feature, 'value'),
            ];
        }

        return $result;
    }

}
