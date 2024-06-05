<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'book_id' => $this->id,
            'book_name' => $this->name,
            'num_of_pages' => $this->num_of_pages,
        ];
    }
}
