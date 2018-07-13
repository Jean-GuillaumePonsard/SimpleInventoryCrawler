<?php

namespace App\Http\Controllers;

use App\Management\ProductManagementInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductManagementInterface $dishwasherManagement)
    {
        return view('home', ['productsList' => $dishwasherManagement->load()]);
    }
}
