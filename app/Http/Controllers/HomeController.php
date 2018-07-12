<?php

namespace App\Http\Controllers;

use App\Management\DishwasherManagement;
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
    public function index(DishwasherManagement $dishwasherManagement)
    {
        return view('home', ['productsList' => $dishwasherManagement->load()]);
    }
}
