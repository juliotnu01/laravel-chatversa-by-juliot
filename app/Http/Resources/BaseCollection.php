<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Concerns\WithPagination;

abstract class BaseCollection extends ResourceCollection
{
    use WithPagination;

    /**
     * The resource class to use for collection items
     */
    abstract protected function getResourceClass(): string;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getPaginatedMeta($this->getResourceClass());
    }
}