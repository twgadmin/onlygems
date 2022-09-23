<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
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
            'supplier'               => 'required',
            'delivery_date'          => 'required|date_format:m-d-Y',
            'order_number'           => 'required'
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
            // 'products_in_purchase.integer' => 'Product in purchase should be a whole number',
        ];
    }
}
