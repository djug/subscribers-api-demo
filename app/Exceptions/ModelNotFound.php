<?php
namespace App\Exceptions;

use Exception;

class ModelNotFound extends Exception
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function render()
    {
        $model = ucfirst($this->model);

        return response()->json(['error' => ['code' => 123, 'message' => "{$model} not found"]], 404);
    }
}
