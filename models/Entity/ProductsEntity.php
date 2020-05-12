<?php
namespace app\models\Entity;


final class ProductsEntity extends Entity
{
    public static function tableName(): string
    {
        $name = "products";

        return $name;
    }
}