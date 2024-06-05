<?php
namespace App\Http\Requests;

class UpdateBookRequest extends BaseFromRequest
{

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'num_of_pages' => 'sometimes|required|integer|min:1',
        ];
    }
}

