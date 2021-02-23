<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
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
            'name'   => 'required|string',
            'phone' => 'required|string|min:7',
        ];
    }
}
