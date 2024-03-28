<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\StoreProduct;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\GetProductsControllerRequest;

class GetProductsController extends Controller
{
    /**
     * Dummy data for the purpose of the test, normally this would be set by a store builder class
     */
    public int $storeId = 3;

    private string $imagesDomain;

    public function __construct()
    {
        $this->imagesDomain = "https://img.tmstor.es/";
    }

    public function __invoke(GetProductsControllerRequest $request)
    {
        if (!$this->storeId) {
            die;

        }

        return $this->getStoreProductsBySectionWithPaginationAndSorting($this->storeId,
            $request->route('section', null),
            $request->get('number', 8),
            $request->get('page', 0),
            $request->get('sort', 'position'));
    }

    private function getStoreProductsBySectionWithPaginationAndSorting(int $storeId, ?Section $section, ?int $number, int $page, string $sort)
    {
        if ($section) {

            $query = $section->products()
                ->getQuery();
        } else {
            $query = StoreProduct::query();
        }
        $query->select('store_products.id',
            'artist_id',
            'type',
            'display_name',
            'name',
            'launch_date',
            'remove_date',
            'store_products.description',
            'available',
            'price',
            'euro_price',
            'dollar_price',
            'image_format',
            'disabled_countries',
            'release_date')
            ->with('artist')
            ->where('store_id', '=', $storeId)
            ->where('available', '=', '1');
        $this->setOrderBy($query, $sort);
        /*        $this->setPagination($query, $number, $page);*/

        $dateTime = time();
        $previewMode = session()->has('preview_mode');
        $currency = session(['currency']);

        $storeProducts = $query->get();

        $products = $storeProducts->map(function (StoreProduct $storeProduct) use ($dateTime, $previewMode, $currency) {
            $mainId = $storeProduct->id;
            $artist = $storeProduct->artist?->name;
            $type = $storeProduct->type;
            $displayName = $storeProduct->display_name;
            $name = $storeProduct->name;
            $launchDate = $storeProduct->launch_date;
            $removeDate = $storeProduct->remove_date;
            $description = $storeProduct->description;
            $available = $storeProduct->available;
            $price = $storeProduct->price;
            $euroPrice = $storeProduct->euro_price;
            $dollarPrice = $storeProduct->dollar_price;
            $imageFormat = $storeProduct->image_format;
            $disabledCountries = $storeProduct->disabled_countries;
            $releaseDate = $storeProduct->release_date;


            if ($launchDate != null && !$previewMode) {
                $launch = strtotime($launchDate);
                if ($launch > time()) {
                    return false;
                }
            }
            //@todo Move this to the model
            if ($removeDate != null) {
                $remove = strtotime($removeDate);
                if ($remove < $dateTime) {
                    $available = 0;
                }
            }

            //check territories @todo Move this to the model
            if ($disabledCountries != '') {
                $countries = explode(',', $disabledCountries);
                $geocode = $this->getGeocode();
                $countryCode = $geocode['country'];
                if (in_array($countryCode, $countries)) {
                    $available = 0;
                }
            }
            switch ($currency) {
                case "USD":
                    $price = $dollarPrice;
                    break;
                case "EUR":
                    $price = $euroPrice;
                    break;
            }
            $return = [];
            if ($available == 1) {
                if (strlen($imageFormat) > 2) {
                    $return['image'] = $this->imagesDomain . "$mainId." . $imageFormat;
                } else {
                    $return['image'] = $this->imagesDomain . "noimage.jpg";
                }

                $return['id'] = $mainId;
                $return['artist'] = $artist;
                $return['title'] = strlen($displayName) > 3 ? $displayName : $name;
                $return['description'] = $description;
                $return['price'] = $price;
                $return['format'] = $type;
                $return['release_date'] = $releaseDate;

            }
            return $return;
        });
        $products = $products->toArray();
        $products['pages'] = ceil($query->count() / $number);
        if (!empty($products)) {
            return $products;
        } else {
            return false;
        }
    }


    protected function setOrderBy(Builder &$builder, $sort)
    {
        switch ($sort) {
            case "az":
                $builder->orderBy('name', 'asc');
                //$order = "ORDER BY name Asc";
                break;
            case "za":
                $builder->orderBy('name', 'desc');
                //  //$order = "ORDER BY name Desc";
                break;
            case "low":
                $builder->orderBy('price', 'asc');
                //$order = "ORDER BY price Asc";
                break;
            case "high":
                $builder->orderBy('price', 'desc');
                //$order = "ORDER BY price Desc";
                break;
            case "old":
                $builder->orderBy('release_date', 'asc');
                //$order = "ORDER BY release_date Asc";
                break;
            case "new":
                $builder->orderBy('release_date', 'desc');
                //$order = "ORDER BY release_date Desc";
                break;
            default:
                $builder->orderBy('store_products.position', 'asc')
                    ->orderBy('release_date', 'desc');

                //$ if ((isset($section) && ($section == "%" || $section == "all"))) {
                //                    $order = "ORDER BY sp.position ASC, release_date DESC";
                //                } else {
                //                    $order = "ORDER BY store_products_section.position ASC, release_date DESC";
                //                }
                //                break;
                break;
        }

    }

    public function getGeocode()
    {
        //Return GB default for the purpose of the test
        return ['country' => 'GB'];
    }

    protected function setPagination(Builder &$builder, $page, $number)
    {
        $page = ($page - 1) * $number;
        $builder->limit($number)
            ->offset($page);
    }

}
