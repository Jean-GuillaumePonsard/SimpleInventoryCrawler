<?php

namespace App\Management;

use App\Product;
use App\SimpleGoutteCrawler;

class SimpleProductManagement implements ProductManagementInterface
{
    protected $defaultUrl = 'https://www.appliancesdelivered.ie/dishwashers';

    protected $sorting = '?sort=price_asc&page=';

    // As there is no other product to currently look for, I set dishwasher as default
    protected $defaultType = 'dishwasher';

    protected $closureToUse = 'ad_dishwasher';

    protected $crawler;

    /**
     * SimpleProductManagement constructor.
     */
    public function __construct()
    {
        $this->crawler = new SimpleGoutteCrawler();
    }

    /**
     * Implementation of the "load" function required by the ProductManagementInterface
     * Updates the data and return the updated collection
     *
     * @return mixed
     */
    public function load()
    {
        $this->update();
        return $this->getAll();
    }

    /**
     * Implementation of the "update" function required by the ProductManagementInterface
     * Updates the products using Goutte crawler to find data
     *
     * @return bool
     */
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

    /**
     * Implementation of the "getAll" function required by the ProductManagementInterface
     * Returns all products ordered by product_name
     *
     * @return mixed
     */
    public function getAll()
    {
        //the default behavior is to order by name Asc
        return Product::orderBy('product_name')->get();
    }


    /**
     * Compares database data with new data to update the database
     *
     * @param $newData
     * @param $storedData (Must be a collection of Products)
     */
    protected function updateProducts($newData, $storedData)
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

                        // If data is not equal, then I must update the database
                        if($productArray != $newData[$keyNewData]) {
                            $this->updateProduct($data, $newData[$keyNewData]);
                        }
                        unset($productArray);
                        // If a product is found, then It is not a new data anymore and can be safely removed
                        // Also reduce the size of the array
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

    /**
     * Creates and inserts a new product using Eloquent
     *
     * @param $inputs
     * @return bool
     */
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

    /**
     * Updates a product using Eloquent
     *
     * @param Product $product
     * @param $inputs
     * @return bool
     */
    protected function updateProduct(Product $product, $inputs)
    {
        $product->fill($inputs);
        $product->saveOrFail();
        return true;
    }

    /**
     * Deactivates a product
     *
     * @param Product $product
     * @return bool
     */
    protected function deactivateProduct(Product $product)
    {
        return $this->updateProduct($product, array("is_active" => false));
    }

    /**
     * Reactivates a product
     *
     * @param Product $product
     * @return bool
     */
    protected function reactivateProduct(Product $product)
    {
        return $this->updateProduct($product, array("is_active" => true));
    }

    /**
     * Returns the default type of product this class will ask to get in the url
     *
     * @return string
     */
    public function getDefaultType()
    {
        return $this->defaultType;
    }

    /**
     * Set the default type of product this class will ask to get in the url
     *
     * @param string $defaultType
     */
    public function setDefaultType($defaultType)
    {
        $this->defaultType = $defaultType;
    }

    /**
     * Get the closure name this class will use with the crawler
     *
     * @return string
     */
    public function getClosureName()
    {
        return $this->closureToUse;
    }

    /**
     * Set the closure name this class will use with the crawler
     * @param string $closureToUse
     */
    public function setClosureName($closureToUse)
    {
        $this->closureToUse = $closureToUse;
    }
}