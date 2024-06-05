<?php
namespace App\Http\Requests;

class StoreBookRequest extends BaseFromRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'num_of_pages' => 'required|integer|min:1',
        ];
    }
}
