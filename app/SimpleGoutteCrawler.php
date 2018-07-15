<?php

namespace App;

use Goutte;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Trait SimpleGoutteCrawler
 *
 * This Class allow to get data from a website using Goutte
 *
 * @package App
 */
Class SimpleGoutteCrawler
{

    private $url;

    public function __construct(String $url)
    {
        $this->url = $url;
    }

    /**
     * findData
     *
     * The main function of the trait which will allow the class to use Goutte to find data.
     * Each closure function must be created in the getClosure switch to ensure the code
     *
     * @param $sorting
     * @param $loopPoint
     * @param $closureName
     * @return array
     * @throws \Exception when Goutte request and filtering fails
     */
    public function findData($sorting, $loopPoint, $closureName)
    {
        $currentPage = 1;
        $results = array();
        $continue = true;

        while ($continue)
        {
            $crawler = Goutte::request('GET', $this->url.$sorting.$currentPage);
            $founds = array();
            try {
                $founds[] = $crawler->filter($loopPoint)->each($this->getClosure($closureName));
            } catch (\Exception $exception) {
                throw $exception;
            }

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

    /**
     * This convert the closure name to the real function required
     *
     * @param $closureName
     * @return \Closure|null
     */
    private function getClosure($closureName)
    {
        switch ($closureName) {
            case 'ad_dishwasher':
                return function (Crawler $node) {
                    return [
                        'product_name' => $node->filter('.product-description h4 a')->text(),
                        'product_img_url' => $node->filter('.product-image img')->attr('src'),
                        'product_price' => trim($node->filter('.product-description h3.section-title')->text(), '$£€')
                    ];
                };

            default:
                return null;
        }
    }

    /**
     * @return String
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param String $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}