<?php

namespace App\Management;

interface ProductManagementInterface
{

    /**
     * Must return a collection of products
     *
     * @return mixed
     */
    public function load();

    /**
     * Should try to update the database
     *
     * @return mixed
     */
    public function update();

    /**
     * Must return the entire collection of products
     *
     * @return mixed
     */
    public function getAll();
}