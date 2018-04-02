<?php
namespace App;

use App\AcceptedField as Field;

class FieldsRepository
{
    private $userId;

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function all()
    {
        $fields = Field::ownedBy($this->userId)->paginate(25);

        return $fields;
    }

    public function get($id)
    {
        $field = Field::Where('id', $id)->ownedBy($this->userId)->first();

        return $field;
    }

    public function create($inputs)
    {
        $inputs = array_merge($inputs, ['user_id' => $this->userId]);
        $title = $inputs['title'];
        $uniqueFieldId = ['user_id' => $this->userId, 'title' => $title]; // to update in case it exists

        $field = Field::updateOrCreate($uniqueFieldId, $inputs);

        return $field;
    }

    public function update($inputs, $id)
    {
        $title = $inputs['title'];
        $inputs = array_merge($inputs, ['user_id' => $this->userId]);
        $field = Field::where('id', $id)->ownedBy($this->userId)->first();
        $field->update($inputs);

        return $field;
    }

    public function delete($id)
    {
        $field = Field::Where('id', $emailOrId)->ownedBy($this->userId)->first();

        if ($field) {
            $field->delete();

            return true;
        }
        return false;
    }


}
