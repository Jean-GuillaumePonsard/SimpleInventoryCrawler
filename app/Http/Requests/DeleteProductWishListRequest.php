<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DeleteProductWishListRequest extends FormRequest
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
     * The product must exist in the user's wish list
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product' => [
                'required',
                Rule::exists('user_product', 'product_id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ]
        ];
    }
}
