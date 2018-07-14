<?php

namespace App\Http\Controllers;

use App\Management\ProductManagementInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return HomeController
     */
    public function __construct()
    {

    }

    /**
     * Show the main page of the web site
     *
     * @param ProductManagementInterface $dishwasherManagement
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ProductManagementInterface $dishwasherManagement)
    {
        return view('home', ['productsList' => $dishwasherManagement->load()]);
    }
}
