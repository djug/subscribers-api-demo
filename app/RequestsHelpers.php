<?php
namespace App;
use App\User;

class RequestsHelpers
{

    public static function getUserIdFromApiKey($request)
    {
        $apiKey = $request->header('X-MailerLite-ApiKey');

        $user = User::where('api_key', $apiKey)->first();
        if ($user) {
            return $user->id;
        }

        return null;
    }
}
