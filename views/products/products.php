<?php
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-md-12">
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'img',
                'format' => 'html',
                'label' => 'Zdjęcie',
                'value' => function ($data) {
                    return Html::img(PRODUCT_IMAGE_PATH . $data['photo'],
                        [
                            'style' => 'width:100px; height: 100px;',
                        ]);

                },
            ],
            [
                'attribute' => 'name',
                'format' => 'text',
                'label' => 'Nazwa',
            ],
            [
                'attribute' => 'description',
                'format' => 'text',
                'label' => 'Opis',
            ],
            [
                'label' => 'Cena',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDecimal($data['price']);
                },
            ],
            [
                'attribute' => 'amount',
                'format' => 'text',
                'label' => 'Ilość',
            ],
            [
                'class' => ActionColumn::class,
                'options' => [
                    'class' => 'gridViewButtonColumn',
                ],
                'template' => '{input} {addToCart}',
                'buttons' => [
                    'addToCart' => function($url, $model, $key) {
                        return Html::a(
                            'Dodaj do koszyka',
                            Url::to(['cart/add', 'id' => $key], true),
                            [
                                'class'=>'btn btn-primary addToCard',
                                'data-id' => $key,
                            ]
                        );
                    },
                    'input' => function($url, $model, $key) {
                        return Html::textInput('amount', '', [
                            'data-id' => $key,
                            'type' => 'number',
                            'class' => 'amount',
                            'min' => '0'
                        ]);
                    },
                ]
            ]
        ],
    ]);
    ?>
</div>