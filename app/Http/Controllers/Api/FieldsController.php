<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AcceptedFieldsRepository;
use App\AcceptedField as Field;
use App\Helpers;
use Validator;

class FieldsController extends Controller
{
    private $acceptedFieldsRepository;

    public function __construct(Request $request, AcceptedFieldsRepository $acceptedFieldsRepository)
    {
        $userId = Helpers::getUserIdFromApiKey($request);

        $this->acceptedFieldsRepository = $acceptedFieldsRepository;
        $this->acceptedFieldsRepository->setUserId($userId);
    }

    public function all()
    {
        return $this->acceptedFieldsRepository->all();
    }

    public function get($id)
    {
        return $this->acceptedFieldsRepository->get($id);
    }

    public function create(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, Field::getValidationRules());

        if ($validator->fails()) {
            $message = Helpers::prettifyErrorMessage($validator->errors());

            return response()->json(['error' => ['code' => 400, 'message' => $message]]);
        }

        return $this->acceptedFieldsRepository->create($inputs);
    }


    public function update(Request $request, $id)
    {
        $inputs  = $request->all();

        return $this->acceptedFieldsRepository->update($inputs, $id);
    }

    public function delete($idOrEmail)
    {
        return $this->acceptedFieldsRepository->delete($idOrEmail);
    }
}
