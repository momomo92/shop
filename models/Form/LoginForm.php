<?php

namespace app\models\Form;

use app\models\Entity\CustomersEntity;
use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;

    private $customer = false;

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function beforeValidate(): bool
    {
        $this->fetchCustomer();

        return parent::beforeValidate();
    }

    public function validatePassword(string $attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();

            if (!$customer || !$customer->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getCustomer(), 0);
        }

        return false;
    }

    private function fetchCustomer(): void
    {
        if (empty($this->customer)) {
            $where = [
                'name' => $this->username,
            ];

            $customer = CustomersEntity::findOne($where);
            $this->setCustomer($customer);
        }

    }

    private function setCustomer(?CustomersEntity $customer): void
    {
        $this->customer = $customer;
    }

    public function getCustomer() :?CustomersEntity
    {
        return $this->customer;
    }
}
