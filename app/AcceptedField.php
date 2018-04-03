<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AcceptedField extends Model
{
    use OwnedByTrait;

    protected $hidden = ['user_id']; // fields to hide when returning the model as json

    protected $fillable = ['title', 'type', 'user_id'];
    public static $acceptedTypes = ['date', 'number', 'string', 'boolean']; // in case we want to accept more types (i.e no need to update the validation rules every time)

    public static function getValidationRules()
    {
        $validationRules = [
                "title" => "required",
                "type" => ["nullable", Rule::in(self::$acceptedTypes)] //nullable since we are defaulting to "string"
                ];

        return $validationRules;
    }
}
