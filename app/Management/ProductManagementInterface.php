<?php

namespace App\Management;

interface ProductManagementInterface
{

    public function load();

    public function update();

    public function getAll();
}