<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Subscriber;
use App\SubscribersRepository;
use App\RequestsHelpers;

class SubscribersController extends Controller
{
    private $subscribersRepository;

    public function __construct(Request $request, SubscribersRepository $subscribersRepository)
    {
        $userId = RequestsHelpers::getUserIdFromApiKey($request);

        $this->subscribersRepository = $subscribersRepository;
        $this->subscribersRepository->setUserId($userId);
    }

    public function all()
    {
        return $this->subscribersRepository->all();
    }

    public function create(Request $request)
    {
        $inputs  = $request->all();
        return $this->subscribersRepository->create($inputs);
    }

    public function get($idOrEmail)
    {
        return $this->subscribersRepository->get($idOrEmail);
    }

    public function update(Request $request, $id)
    {
        $inputs  = $request->all();

        return $this->subscribersRepository->update($inputs, $id);
    }

    public function delete($idOrEmail)
    {
        return $this->subscribersRepository->delete($idOrEmail);
    }
}
