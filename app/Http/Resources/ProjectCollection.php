<?php

namespace App\Http\Resources;

class ProjectCollection extends BaseCollection
{
    protected function getResourceClass(): string
    {
        return ProjectResource::class;
    }
}