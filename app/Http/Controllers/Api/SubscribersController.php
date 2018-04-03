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
use App\Exceptions\ModelNotFound;

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

            return response()->json(['error' => ['code' => 400, 'message' => $message]], 400);
        }

        $subscriber = $this->subscribersRepository->create($inputs);
        if (isset($inputs['fields'])) {
            $fields = $inputs['fields'];
            $this->fieldsRepository->setsubscriberId($subscriber->id);
            $this->fieldsRepository->createOrUpdateMultiple($fields);
        }

        return $subscriber;
    }

    public function get($idOrEmail)
    {
        $subscriber = $this->subscribersRepository->get($idOrEmail);

        if (! $subscriber) {
            throw new ModelNotFound("subscriber");
        }
        return $subscriber;
    }

    public function update(Request $request, $idOrEmail)
    {
        $inputs  = $request->all();
        $subscriber = $this->get($idOrEmail);
        if (! $subscriber) {
            return response()->json(['error' => ['code' => 123, 'message' => "Subscriber not found"]], 404);
        }

        $updatedSubscriberData = array_merge($subscriber->toArray(), $inputs);

        $updatedSubscriberData['domain'] = Helpers::getDomainFromEmail($updatedSubscriberData['email']);

        $validator = Validator::make($updatedSubscriberData, Subscriber::getValidationRules());

        if ($validator->fails()) {
            $message = Helpers::prettifyErrorMessage($validator->errors());

            return response()->json(['error' => ['code' => 400, 'message' => $message]], 400);
        }

        $subscriber = $this->subscribersRepository->update($updatedSubscriberData, $idOrEmail);

        if (isset($inputs['fields'])) {
            $fields = $inputs['fields'];
            $this->fieldsRepository->setsubscriberId($subscriber->id);
            $this->fieldsRepository->createOrUpdateMultiple($fields);
        }
        return $subscriber;
    }

    public function delete($idOrEmail)
    {
        $subscriber = $this->get($idOrEmail);

        $result = $this->subscribersRepository->delete($idOrEmail);
        if ($result) {
            return response()->json("", 204);
        }
    }
}
