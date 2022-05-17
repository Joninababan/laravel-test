<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {



        return [
            // @TODO implement
            'data' => [
                'id' => $this->id,
                'isbn' => $this->isbn,
                'description' => $this->description,
                'title' => $this->title,
                'published_year' => $this->published_year,
                'authors' => [
                    'id' => $this->authors()->first()->id,
                    'name' => $this->authors()->first()->name,
                    'surname' => $this->authors()->first()->surname,
                ]
            ],
            'review' => [
                'avg' => $this->reviews()->where('book_id','=',$this->id)->avg('review'),
                'count' => $this->reviews()->where('book_id','=',$this->id)->count(),
            ],
        ];

    }
}
