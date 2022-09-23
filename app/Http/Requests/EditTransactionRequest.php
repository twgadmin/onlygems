<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTransactionRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name'           => 'required',
            'brand_name'             => 'required',
            'seller_name'            => 'required',
            'upc_code'               => 'required',
            'style_number'           => 'required',
            'color'                  => 'required',
            'size'                   => 'required',
            'country_of_origin'      => 'required',
            'products_in_purchase'   => 'required|integer',
            'purchase_price'         => 'required|numeric',
            'estimated_resell_value' => 'required|numeric',
            'shipping_cost'          => 'numeric',
            'other_costs'            => 'numeric',
            // 'estimated'              => 'required',
            // 'notes'                  => '',
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'products_in_purchase.integer' => 'Product in purchase should be a whole number',
        ];
    }
}
