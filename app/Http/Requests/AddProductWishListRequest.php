<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddProductWishListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * The product must exist in the product table
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product' => 'required|exists:products,id|unique:user_product,product_id,null,null,user_id,'.Auth::id()
        ];
    }
}
