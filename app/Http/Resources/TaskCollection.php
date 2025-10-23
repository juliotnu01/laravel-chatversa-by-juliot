<?php

namespace App\Http\Resources;

class TaskCollection extends BaseCollection
{
    protected function getResourceClass(): string
    {
        return TaskResource::class;
    }
}