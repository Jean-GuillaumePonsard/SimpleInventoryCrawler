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

    public function __construct()
    {

    }

    /**
     * findData
     *
     * The main function of the trait which will allow the class to use Goutte to find data.
     * Each closure function must be created in the getClosure switch to ensure the code
     *
     * @param $defaultUrl
     * @param $sorting
     * @param $loopPoint
     * @param $closureName
     * @return array
     * @throws \Exception when Goutte request and filtering fails
     */
    public function findData($defaultUrl, $sorting, $loopPoint, $closureName)
    {
        $currentPage = 1;
        $results = array();
        $continue = true;

        while ($continue)
        {
            $crawler = Goutte::request('GET', $defaultUrl.$sorting.$currentPage);
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

    private function getClosure($closureName)
    {
        switch ($closureName) {
            case 'ad_dishwasher':
                return function (Crawler $node) {
                    return [
                        'd_name' => $node->filter('.product-description h4 a')->text(),
                        'd_img_url' => $node->filter('.product-image img')->attr('src')
                    ];
                };

            default:
                return null;
        }
    }

}