<?php

namespace App\Management;

interface ProductCrawlerInterface
{
    public function load();

    public function update();

    public function getAll();
}