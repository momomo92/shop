<?php

namespace app\controllers;

use app\models\Entity\ProductsEntity;
use app\models\Form\ContactForm;
use Yii;

class CartController extends Controller
{
    public function actionAdd(int $id, int $amount): bool
    {
        $result = false;
        $session = Yii::$app->session;

        if (!empty($id)) {
            $cart = !empty($session['cart']) ? $session['cart'] : [];

            $product = ProductsEntity::findOne($id);

            if ($product->amount >  $amount) {
                $product->amount -= $amount;
                $product->save();

                if ( array_key_exists($id, $cart)) {
                    $cart[$id]['amount'] += $amount;
                } else {
                    $cart[$id] = [
                        'amount' => $amount,
                    ];
                }

                $session['cart'] = $cart;

                $result = true;
            }
        }

        return $result;
    }

    public function actionCart()
    {
        $cart = $this->fetchCustomerCart();
        $totalPrice = 0;
        $session = Yii::$app->session;

        foreach (array_column($cart, 'totalPrice') as $price) {
            $totalPrice += $price;
        }

        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->contact()) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            $session['cart'] = [];

            return $this->refresh();
        }

        $this->view->title  = "Koszyk";

        return $this->render('cart', [
            'cart' => $cart,
            'model' => $model,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function actionRemove(int $id): bool
    {
        $session = Yii::$app->session;

        $cart = !empty($session['cart']) ? $session['cart'] : [];

        if (array_key_exists($id, $cart)) {
            unset($cart[$id]);
            $session['cart'] = $cart;
        }

        $cart = $this->fetchCustomerCart();
        $totalPrice = 0;

        foreach (array_column($cart, 'totalPrice') as $price) {
            $totalPrice += $price;
        }

        $result = !empty($totalPrice) ? $totalPrice : false;

        return $result;
    }

    private function fetchCustomerCart(): array
    {
        $session = Yii::$app->session;
        $cart = isset($session['cart']) ? $session['cart'] : [];
        $totalPrice = 0;

        if (isset($session['cart'])) {
            $products = ProductsEntity::findAll(array_keys($session['cart']));

            foreach ($products as $product) {
                $cart[$product->id]['productData'] = $product->toArray();
            }
        }

        foreach ($cart as $key => $element) {
            if (!empty($element['amount']) && !empty($element['productData']['price'])) {
                $price = $element['amount'] * $element['productData']['price'];
                $totalPrice += $price;
                $cart[$key]['totalPrice'] = $price;
            }
        }

        return $cart;
    }
}
