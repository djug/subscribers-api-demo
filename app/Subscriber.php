<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Rules\ActiveDomain;

class Subscriber extends Model
{
    use OwnedByTrait;

    protected $fillable = ['email', 'name', 'state', 'user_id'];

    protected $hidden = ['user_id'];

    public static function getValidationRules()
    {
        $validationRules = [
                    'email' => 'required|email',
                    "domain" => ['required', new ActiveDomain],
                    'name'  => 'alpha|nullable',
                    'state' => 'nullable|in:active,unsubscribed,junk,bounced,unconfirmed'
                ];

        return $validationRules;
    }


}
