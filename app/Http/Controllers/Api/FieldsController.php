<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AcceptedFieldsRepository;
use App\AcceptedField as Field;
use App\Helpers;
use Validator;
use App\Exceptions\ModelNotFound;

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
        $field = $this->acceptedFieldsRepository->get($id);
        if (! $field) {
            throw new ModelNotFound("field");
        }
        return $field;
    }

    public function create(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, Field::getValidationRules());

        if ($validator->fails()) {
            $message = Helpers::prettifyErrorMessage($validator->errors());

            return response()->json(['error' => ['code' => 400, 'message' => $message]], 400);
        }

        return $this->acceptedFieldsRepository->create($inputs);
    }


    public function update(Request $request, $id)
    {
        $inputs  = $request->all();

        $field = $this->get($id);

        $updatedFieldData = array_merge($field->toArray(), $inputs);

        $validator = Validator::make($updatedFieldData, Field::getValidationRules());

        if ($validator->fails()) {
            $message = Helpers::prettifyErrorMessage($validator->errors());

            return response()->json(['error' => ['code' => 400, 'message' => $message]], 400);
        }

        return $this->acceptedFieldsRepository->update($inputs, $id);
    }

    public function delete($idOrEmail)
    {
        $field = $this->get($idOrEmail);

        $result = $this->acceptedFieldsRepository->delete($idOrEmail);
        if ($result) {
            return response()->json("", 204);
        }
    }
}
