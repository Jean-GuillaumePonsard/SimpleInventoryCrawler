<?php

namespace App\Management;

use App\Product;
use App\SimpleGoutteCrawler;

class SimpleDishwasherManagement implements ProductManagementInterface
{
    protected $defaultUrl = 'https://www.appliancesdelivered.ie/dishwashers';

    protected $sorting = '?sort=price_asc&page=';

    protected $defaultType = 'dishwasher';

    protected $closureToUse = 'ad_dishwasher';

    protected $crawler;

    public function __construct()
    {
        $this->crawler = new SimpleGoutteCrawler();
    }

    public function load()
    {
        $this->update();
        return $this->getAll();
    }

    public function update()
    {
        // First I need to use Goutte
        // 1st page only right now
        try {
            $products = $this->crawler->findData($this->defaultUrl, $this->sorting, '.search-results-product.row', $this->closureToUse);
            if(!empty($products)) {
                // Then get data from the database
                $storedData = $this->getAll();
                $this->updateProducts($products, $storedData);
            }
        } catch (\Throwable $exception) {
            // An error happened (Database or Goutte failed)
            return false;
        }
        return true;
    }

    public function getAll()
    {
        //the default behavior is to order by name Asc
        return Product::orderBy('product_name')->get();
    }


    /**
     * @param $newData
     * @param $storedData (Must be a collection of Products)
     */
    private function updateProducts($newData, $storedData)
    {
        // if $storedData is an empty collection then insert all the data
        if(!empty($newData)) {
            // I will just match the reference, then the meta data
            // Like that I will be able to update product that changed just their prices or image

            // This allow to keep the same key
            $newDataProductName = array_diff(array_combine(array_keys($newData), array_column($newData, 'product_name')), [null]);

            if(!$storedData->isEmpty()) {
                // storedData is an Array of Product object
                foreach ($storedData as $keyStoredData => $data) {
                    $keyNewData = array_search($data->product_name, $newDataProductName, true);

                    if($keyNewData !== false) {
                        // Adding is_active true to update the product if it was deactivated
                        $newData[$keyNewData]['is_active'] = true;
                        // Check if the stored data is up to date for the other fields
                        $productArray = array("product_name" => $data->product_name,
                                                "product_img_url" => $data->product_img_url,
                                                "product_price" => $data->product_price,
                                                "is_active" => $data->is_active);

                        if($productArray != $newData[$keyNewData]) {
                            $this->updateProduct($data, $newData[$keyNewData]);
                        }
                        unset($productArray);
                        unset($newData[$keyNewData]);
                    } else {
                        // If the storedData is already deactivated, then I don't need to update it
                        if($data->is_active) {
                            // Deactivate outdated content
                            $this->deactivateProduct($data);
                        }
                    }
                }
            }

            // Store all the data to the database
            foreach ($newData as $currentData) {
                $this->insertNewProduct($currentData);
            }
        }
    }

    protected function insertNewProduct($inputs)
    {
        $dishwasher = new Product();
        $dishwasher->product_name = $inputs['product_name'];
        $dishwasher->product_img_url = $inputs['product_img_url'];
        $dishwasher->product_price = $inputs['product_price'];
        $dishwasher->is_active = true;

        $dishwasher->saveOrFail();
        return true;
    }

    protected function updateProduct(Product $product, $inputs)
    {
        $product->fill($inputs);
        $product->saveOrFail();
        return true;
    }

    protected function deactivateProduct(Product $product)
    {
        return $this->updateProduct($product, array("is_active" => false));
    }

    protected function reactivateProduct(Product $product)
    {
        return $this->updateProduct($product, array("is_active" => true));
    }


}