<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscriber;

class SubscribersController extends Controller
{
    public function all()
    {
        return 'all';
    }

    public function createSubscriber(Request $request)
    {
        $userId = $request->header('user-id');

        $inputs  = $request->all();
        $email = $inputs['email'];
        $name = $inputs['name'];
        $inputs = array_merge($inputs, ['user_id' => $userId]);
        $uniqueSubscriberId = ['user_id' => $userId, 'email' => $email];

        $subscriber = Subscriber::updateOrCreate($uniqueSubscriberId, $inputs);

        return $subscriber;
    }
}
