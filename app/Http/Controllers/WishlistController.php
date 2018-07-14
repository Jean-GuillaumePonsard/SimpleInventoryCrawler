<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductWishListRequest;
use App\Http\Requests\DeleteProductWishListRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Return the instance of the authenticated user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function user()
    {
        return Auth::user();
    }

    /**
     * Create a new controller instance.
     *
     * @return WishlistController
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax', ['only' => ['addProduct', 'deleteProduct']]);
    }

    /**
     * Show the user's wish list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('wishlist', ['productsList' => $this->user()->products()->orderBy('product_name')->get()]);
    }

    /**
     * addProduct function:
     * Allow to add a product into the user's wish list
     *
     * @param AddProductWishListRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function addProduct(AddProductWishListRequest $request)
    {
        $this->user()->products()->attach($request->all(array('product')));

        return response()->json();
    }

    /**
     * deleteProduct:
     * Remove the product from the authenticated user's wish list
     *
     * @param DeleteProductWishListRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function deleteProduct(DeleteProductWishListRequest $request)
    {
        $this->user()->products()->detach($request->all(array('product')));

        return response()->json();
    }
}
