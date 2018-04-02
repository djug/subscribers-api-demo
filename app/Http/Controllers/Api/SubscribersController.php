<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Subscriber;
use App\SubscribersRepository;
use App\FieldsRepository;
use App\Helpers;
use Validator;

class SubscribersController extends Controller
{
    private $subscribersRepository;
    private $fieldsRepository;

    public function __construct(Request $request, SubscribersRepository $subscribersRepository, FieldsRepository $fieldsRepository)
    {
        $userId = Helpers::getUserIdFromApiKey($request);

        $this->subscribersRepository = $subscribersRepository;
        $this->subscribersRepository->setUserId($userId);
        $this->fieldsRepository = $fieldsRepository;
        $this->fieldsRepository->setUserId($userId);
    }

    public function all()
    {
        return $this->subscribersRepository->all();
    }

    public function create(Request $request)
    {
        $inputs = $request->all();


        $inputs['domain'] = Helpers::getDomainFromEmail($inputs['email']);

        $validator = Validator::make($inputs, Subscriber::getValidationRules());

        if ($validator->fails()) {
            $message = Helpers::prettifyErrorMessage($validator->errors());

            return response()->json(['error' => ['code' => 400, 'message' => $message]]);
        }


        $subscriber = $this->subscribersRepository->create($inputs);
        $fields = $inputs['fields'];

        $this->fieldsRepository->setsubscriberId($subscriber->id);
        $this->fieldsRepository->createMultiple($fields);
        return $subscriber;
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
