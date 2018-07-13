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
        return Product::all();
    }


    /**
     * @param $newData
     * @param $storedData (Must be a collection of Products)
     */
    private function updateProducts($newData, $storedData)
    {
        // if $storedData is an empty collection then insert all the data
        if(!empty($newData)) {
            if($storedData->isEmpty()) {
                // Store all the data to the database
                foreach ($newData as $currentData)
                {
                    $this->insertNewProduct($currentData);
                }
            } else {
                // Array of Product object
                $toDeactivate = array();
                $toReactivate = array();

                foreach ($storedData as $keyStoredData => $data) {
                    $testArray = array("d_name" => $data->d_name, "d_img_url" => $data->d_img_url);
                    $keyNewData = array_search($testArray, $newData, true);

                    if($keyNewData !== false) {
                        unset($newData[$keyNewData]);
                        // If the storedData is deactivated, it needs to be reactivated
                        if(!$data->is_active) {
                            $toReactivate[] = $storedData[$keyStoredData];
                        }
                    } else {
                        // If the storedData is already deactivated, then I don't need to update it
                        if($data->is_active) {
                            $toDeactivate[] = $storedData[$keyStoredData];
                        }
                    }
                }

                // Store all the data to the database
                // TODO Factorise this code with the one above
                foreach ($newData as $currentData) {
                    $this->insertNewProduct($currentData);
                }

                // Deactivate outdated content
                foreach ($toDeactivate as $productToDeactivate) {
                    $this->deactivateProduct($productToDeactivate);
                }

                // Reactivate useful content
                foreach ($toReactivate as $productToReactivate) {
                    $this->reactivateProduct($productToReactivate);
                }
            }
        }
    }

    protected function insertNewProduct($inputs)
    {
        $dishwasher = new Product();
        $dishwasher->d_name = $inputs['d_name'];
        $dishwasher->d_img_url = $inputs['d_img_url'];
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