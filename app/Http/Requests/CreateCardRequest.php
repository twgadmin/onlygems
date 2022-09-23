<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCardRequest extends FormRequest
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
            'player_name' => 'required',
            // 'grading_co' => 'required',
            // 'grading_co_serial_number' => 'required',
            'year' => 'required',
            'set' => 'required',
            'card_number' => 'required',
            // 'parralel' => 'required',
            'grade' => 'required',
            // 'category' => 'required'
        ];
    }
}
