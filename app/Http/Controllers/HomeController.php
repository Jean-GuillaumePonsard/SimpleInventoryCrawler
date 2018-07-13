<?php

namespace App\Http\Controllers;

use App\Management\ProductCrawlerInterface;
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
    public function index(ProductCrawlerInterface $dishwasherManagement)
    {
        return view('home', ['productsList' => $dishwasherManagement->load()]);
    }
}
