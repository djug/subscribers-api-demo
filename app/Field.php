<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use OwnedByTrait;

    protected $fillable = ['title', 'value', 'user_id', 'subscriber_id', 'accepted_field_id'];

    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        $acceptedField = AcceptedField::find($this->accepted_field_id);
        return strtoupper($acceptedField->type);
    }
    protected $hidden = ['id', 'user_id', 'subscriber_id', 'created_at', 'updated_at', 'accepted_field_id'];
}
