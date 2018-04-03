<?php
namespace App;

use App\Subscriber;

class SubscribersRepository
{
    private $userId;

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function all()
    {
        $subscribers = Subscriber::ownedBy($this->userId)->paginate(25);

        return $subscribers;
    }

    public function get($idOrEmail)
    {
        $subscriber = Subscriber::where('email', $idOrEmail)->orWhere('id', $idOrEmail)->ownedBy($this->userId)->first();

        return $subscriber;
    }

    public function create($inputs)
    {

        $inputs = array_merge($inputs, ['user_id' => $this->userId]);
        $email = $inputs['email'];
        $uniqueSubscriberId = ['user_id' => $this->userId, 'email' => $email]; // to update in case it exists

        $subscriber = Subscriber::updateOrCreate($uniqueSubscriberId, $inputs);

        return $subscriber;
    }

    public function update($inputs, $idOrEmail)
    {
        $email = $inputs['email'];
        $inputs = array_merge($inputs, ['user_id' => $this->userId]);
        $uniqueSubscriberId = ['user_id' => $this->userId, 'email' => $email];
        $subscriber = Subscriber::where('email', $idOrEmail)->orWhere('id', $idOrEmail)->ownedBy($this->userId)->first();
        $subscriber->update($inputs);

        return $subscriber;
    }

    public function delete($emailOrId)
    {
        $subscriber = Subscriber::where('email', $emailOrId)->orWhere('id', $emailOrId)->ownedBy($this->userId)->first();

        if ($subscriber) {
            $subscriber->delete();

            return true;
        }
        return false;
    }


}
