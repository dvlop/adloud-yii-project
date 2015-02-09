<?php
namespace models\validator;
use models\User;
use ValidationException;

/**
 * @property User $nextLayer
 */
class UserValidator extends ValidatorLayer
{

    public function save()
    {
        if ($this->nextLayer->getId() !== null) {
            throw new \exceptions\ValidationException('this object is already initialized. Create new instance or use "update" method');
        }

        if (empty($this->nextLayer->email)) {
            throw new \exceptions\ValidationException('email is not set');
        } else {
            if (!filter_var($this->nextLayer->email, FILTER_VALIDATE_EMAIL)) {
                throw new \exceptions\ValidationException('email is not valid');
            }
            if ($this->nextLayer->nextLayer->getByEmail($this->nextLayer->email)) {
                throw new \exceptions\ValidationException('email already exists');
            }
        }

        if (empty($this->nextLayer->password)) {
            throw new \exceptions\ValidationException('password is not set');
        }

        if (empty($this->nextLayer->fullName)) {
            throw new \exceptions\ValidationException('fullName is not set');
        }

        return $this->nextLayer->save();
    }

    public function addMoneyBalance($amount, $description)
    {
        if (empty($description)) {
            throw new \exceptions\ValidationException('description is empty');
        }

        if ($this->nextLayer->getId() == null) {
            throw new \exceptions\ValidationException('user is not initialized');
        }

        if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
            throw new \exceptions\ValidationException('invalid amount');
        }
        return $this->nextLayer->addMoneyBalance($amount, $description);
    }

    public function getById($id)
    {

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \exceptions\ValidationException('invalid id');
        }

        return $this->nextLayer->initById($id);
    }

    public function getByEmail($email)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \exceptions\ValidationException('invalid email');
        }

        return $this->nextLayer->initByEmail($email);
    }

    public function checkPassword($password)
    {
        if ($this->nextLayer->getId() == null) {
            throw new \exceptions\ValidationException('user is not initialized');
        }

        return $this->nextLayer->checkPassword($password);
    }

    public function update($id = null){
        if (!empty($this->nextLayer->email) && !filter_var($this->nextLayer->email, FILTER_VALIDATE_EMAIL)) {
            throw new \exceptions\ValidationException('email is not valid');
        }
        return $this->nextLayer->update($id);
    }
}