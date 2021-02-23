<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()) {
            return true;
        }

        return false;
    }


    public function rules()
    {
        return [
            'name'   => 'required|string|min:3',
            'phone' => 'required|string',
        ];
    }

}
