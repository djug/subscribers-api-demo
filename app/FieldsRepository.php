<?php
namespace App;

use App\Field;
use App\AcceptedField;

class FieldsRepository
{
    private $userId;
    private $subscriberId;

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function setsubscriberId($subscriberId)
    {
        $this->subscriberId = $subscriberId;

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

    public function create($title, $value)
    {
        Field::create('');
    }

    public function createMultiple($fields)
    {

        foreach ($fields as $title => $value) {
            $fieldInformation = AcceptedField::where('title', $title)->ownedBy($this->userId)->first();
            $createdFields = [];
            if ($fieldInformation) {

                $existingField = ['title' => $title,
                                'subscriber_id' => $this->subscriberId,
                                'user_id' => $this->userId,
                            ];
                $field = Field::updateOrcreate(
                    $existingField,
                    array_merge(
                        $existingField,
                        [   'value' => $value,
                            'accepted_field_id' => $fieldInformation->id]
                    )
                );
                $createdFields[] = $field->toArray();
            }
        }
        return $createdFields;
    }

    public function update($inputs, $id)
    {
    }

    public function delete($id)
    {
        $field = Field::Where('id', $id)->ownedBy($this->userId)->first();

        if ($field) {
            $field->delete();

            return true;
        }
        return false;
    }
}
