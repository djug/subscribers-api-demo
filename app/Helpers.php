<?php
namespace App;
use App\User;

class Helpers
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

    public static function prettifyErrorMessage($errors)
    {
        $message =  implode ($errors->all(), ' and ');

        return $message;
    }

    public static function getDomainFromEmail($email)
    {
        $explodedEmail = explode('@', $email);
        $domain = array_pop($explodedEmail);

        return $domain;
    }
}
