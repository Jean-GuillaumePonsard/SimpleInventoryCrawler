<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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

    protected function validator(array $data)
    {
        return Validator::make($data, [
           'product' => 'required|exists:products,id|unique:user_product,product_id,null,null,user_id,'.Auth::id()
        ]);
    }

    // TODO use a true validator
    /**
     * addProduct function:
     * Allow to add a product into the user's wish list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function addProduct(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            // TODO set Error
            throw new ValidationException($validator, null, 'error');
        }

        $this->user()->products()->attach(array($request->product));

        return response()->json();
    }

    /**
     * deleteProduct:
     * Remove the product from the authenticated user's wish list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function deleteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product' => [
                'required',
                Rule::exists('user_product', 'product_id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ]
        ]);

        if($validator->fails()) {
            // TODO set Error
            throw new ValidationException($validator, null, 'error');
        }

        $this->user()->products()->detach(array($request->product));

        return response()->json();
    }
}
