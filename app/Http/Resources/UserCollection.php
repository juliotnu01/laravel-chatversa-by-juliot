<?php

namespace App\Http\Resources;

class UserCollection extends BaseCollection
{
    protected function getResourceClass(): string
    {
        return UserResource::class;
    }
}