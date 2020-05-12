<?php
namespace app\models\Entity;

use yii\web\IdentityInterface;

final class CustomersEntity extends Entity implements IdentityInterface
{
    public $auth_key;
    public $access_token;

    public static function tableName(): string
    {
        $name = "customers";

        return $name;
    }

    public function beforeSave($insert): bool
    {
        $result = false;

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            $result = true;
        }
        return $result;
    }

    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        $passwordEncoded = hash_hmac('sha256', $password, "ksSva39weXQdRMQhHQ17BwCJa0s1yBH");

        $result = $passwordEncoded === $this->password;

        return $result;
    }
}