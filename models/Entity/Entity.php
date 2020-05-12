<?php
namespace app\models\Entity;

use yii\db\ActiveRecord;

abstract class Entity extends ActiveRecord
{
    public static function findWithoutDeleted()
    {
        $result = parent::find();
        $result->asArray();
        $result->where(['deleted' => null]);

        return $result;
    }
}