<?php

namespace App\Management;

use App\Product;
use Goutte;
use Mockery\Exception;

class DishwasherManagement
{

    protected $defaultUrl = 'https://www.appliancesdelivered.ie/dishwashers';

    protected $sorting = '?sort=price_asc&page=';

    protected $defaultType = 'dishwasher';

    public function load()
    {
        // First I need to use Goutte
        // 1st page only right now
        try {
            $dishwashers = $this->findDishWashersByGoutte();
            // Then get data from the database
            $storedData = $this->getAll();

            $this->updateProducts($dishwashers, $storedData);
        } catch (\Exception $exception) {
            // TODO : Ignore if errors while loading new data ?
        }


        return $this->getAll();
    }

    private function findDishWashersByGoutte()
    {
        $currentPage = 1;
        $results = array();
        $continue = true;

        while ($continue)
        {
            $crawler = Goutte::request('GET', $this->defaultUrl.$this->sorting.$currentPage);

            $founds = array();
            $founds[] = $crawler->filter('.search-results-product.row')->each(function ($node) {
                return [
                    'd_name' => $node->filter('.product-description h4 a')->text(),
                    'd_img_url' => $node->filter('.product-image img')->attr('src')
                ];
            });

            if(empty($founds[0])) {
                $continue = false;
            } else {
                foreach ($founds[0] as $key => $found) {
                    $results[] = $found;
                }
            }

            unset($founds);

            $currentPage++;
        }

        return $results;
    }

    public function getAll()
    {
        $storedDishwasher = Product::all();
        return $storedDishwasher;
    }

    /**
     * @param $newData
     * @param $storedData (Must be a collection of Products)
     */
    private function updateProducts($newData, $storedData)
    {
        // if $storedData is an empty collection then insert all the data
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