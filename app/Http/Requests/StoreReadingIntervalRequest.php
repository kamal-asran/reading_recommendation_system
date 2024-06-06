<?php
namespace App\Http\Requests;

class StoreReadingIntervalRequest extends BaseFromRequest
{
   
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'start_page' => 'required|integer|min:1',
            'end_page' => 'required|integer|gte:start_page',
        ];
    }
}
