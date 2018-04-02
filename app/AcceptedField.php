<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AcceptedField extends Model
{
    use OwnedByTrait;

    protected $hidden = ['user_id'];

    protected $fillable = ['title', 'type', 'user_id'];
    public static $acceptedTypes = ['date', 'number', 'string', 'boolean'];
    public static function getValidationRules()
    {
        $validationRules = [
                    'title' => 'required',
                    "type" => ['nullable', Rule::in(self::$acceptedTypes)]
                ];

        return $validationRules;
    }
}
