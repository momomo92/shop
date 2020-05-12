<?php

namespace app\controllers;

use app\models\Entity\ProductsEntity;
use yii\data\ActiveDataProvider;

class ProductsController extends Controller
{
    public function actionProducts(): string
    {
        $query = ProductsEntity::findWithoutDeleted();

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 4,
            ],
            'sort' => false,
            'query' => $query,
        ]);

        $this->view->title  = "Produkty";

        return $this->render('products', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
