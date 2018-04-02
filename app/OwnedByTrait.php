<?php

namespace App;

Trait OwnedByTrait
{
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
