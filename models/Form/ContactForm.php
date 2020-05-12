<?php

namespace app\models\Form;

use app\models\Entity\ProductsEntity;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $street;
    public $postcode;
    public $city;
    public $phone;
    public $nip;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[
                'city',
                'email',
                'name',
                'nip',
                'phone',
                'postcode',
                'street',
            ], 'required'],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'city' => 'Miasto',
            'email' => 'Mail',
            'name' => 'Imie i Nazwisko',
            'nip' => 'NIP',
            'phone' => "Telefon",
            'postcode' => 'Kod pocztowy',
            'street' => 'Ulica',
        ];
    }

    public function contact()
    {
        $result = false;

        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setSubject("Zamówienie")
                ->setTextBody($this->buildMailBody())
                ->send();

            $result = true;
        }

        return $result;
    }

    private function buildMailBody(): string
    {
        $body = "Zamówienie zostało wysłane!<br>";

        $body .= "Imie i nazwisko: " . $this->name . "<br>";
        $body .= "Ulica: " . $this->street . "<br>";
        $body .= "Miasto: " . $this->city . "<br>";
        $body .= "Kod Pocztowy: " . $this->postcode . "<br>";
        $body .= "Nr. telefonu: " . $this->phone . "<br>";
        $body .= "NIP " . $this->nip . "<br>";

        $cart = $this->fetchCustomerCart();
        $iteration = 1;

        foreach ($cart as $element) {
            $body .= $iteration .'. '. $element['productData']['name'] . ' | ' .  $element['amount']  . ' | ' . $element['amount'] . '<br>';
            $iteration++;
        }

        $totalPrice = 0;

        foreach (array_column($cart, 'totalPrice') as $price) {
            $totalPrice += $price;
        }

        $body .= "Całkowita cena: " . $totalPrice;

        return $body;
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
