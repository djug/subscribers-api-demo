<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FieldsRepository;
use App\AcceptedField as Field;
use App\Helpers;
use Validator;

class FieldsController extends Controller
{
    private $fieldsRepository;

    public function __construct(Request $request, FieldsRepository $fieldsRepository)
    {
        $userId = Helpers::getUserIdFromApiKey($request);

        $this->fieldsRepository = $fieldsRepository;
        $this->fieldsRepository->setUserId($userId);
    }

    public function all()
    {
        return $this->fieldsRepository->all();
    }

    public function get($id)
    {
        return $this->fieldsRepository->get($id);
    }

    public function create(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, Field::getValidationRules());

        if ($validator->fails()) {
            $message = Helpers::prettifyErrorMessage($validator->errors());

            return response()->json(['error' => ['code' => 400, 'message' => $message]]);
        }

        return $this->fieldsRepository->create($inputs);
    }


    public function update(Request $request, $id)
    {
        $inputs  = $request->all();

        return $this->fieldsRepository->update($inputs, $id);
    }

    public function delete($idOrEmail)
    {
        return $this->fieldsRepository->delete($idOrEmail);
    }
}
