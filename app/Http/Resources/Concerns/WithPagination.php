<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Http\Resources\Json\ResourceCollection;

trait WithPagination
{
    /**
     * Get pagination metadata
     */
    private function getPaginationMeta(): array
    {
        return [
            'current_page' => $this->resource->currentPage(),
            'per_page' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'last_page' => $this->resource->lastPage(),
            'from' => $this->resource->firstItem(),
            'to' => $this->resource->lastItem(),
        ];
    }

    /**
     * Get the pagination meta with custom resource
     */
    protected function getPaginatedMeta($resourceClass): array
    {
        return [
            'data' => $resourceClass::collection($this->collection),
            'meta' => $this->getPaginationMeta()
        ];
    }
}