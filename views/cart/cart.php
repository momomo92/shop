<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-md-12">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Zdjęcie</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena</th>
                <th>Ilość</th>
                <th>Cena całkowita</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $key => $element): ?>
                <tr data-id="<?php echo $key; ?>">
                    <td>
                        <?php echo Html::img(PRODUCT_IMAGE_PATH . $element['productData']['photo'], [
                            'style' => 'width:100px; height: 100px;',
                        ]); ?>
                    </td>
                    <td>
                        <?php echo $element['productData']['name']; ?>
                    </td>
                    <td>
                        <?php echo $element['productData']['description']; ?>
                    </td>
                    <td>
                        <?php echo Yii::$app->formatter->asDecimal($element['productData']['price']); ?>
                    </td>
                    <td>
                        <?php echo $element['amount']; ?>
                    </td>
                    <td id="elementTotalPrice" data-id="<?php echo $key; ?>">
                        <?php echo Yii::$app->formatter->asDecimal($element['totalPrice']); ?>
                    </td>
                    <td>
                        <?php echo Html::a(
                            'Usuń',
                            Url::to(['cart/remove', 'id' => $key], true),
                            [
                                'class'=>'btn btn-danger removeFromCart',
                                'data-id' => $key,
                            ]
                        );
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="7" style="text-align: right" id="totalPrice"><?php echo Yii::$app->formatter->asDecimal($totalPrice); ?></td>
            </tr>
        </tbody>
    </table>
</div>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        Dziekujemy za złożenie zamówienia!
    </div>

    <p>
        Na Twój email zostało wysłanie potwierdzenie zamówienia
    </p>

<?php else: ?>
    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?php echo $form->field($model, 'name')
                ->textInput([
                    'autofocus' => true,
                    'value' => !empty(Yii::$app->user->identity->name) ? Yii::$app->user->identity->name : "",
                ]) ?>
            <?php echo $form->field($model, 'email')
                ->textInput([
                    'value' => !empty(Yii::$app->user->identity->email) ? Yii::$app->user->identity->email : "",
                ]); ?>
            <?php echo $form->field($model, 'street')
                ->textInput([
                    'value' => !empty(Yii::$app->user->identity->street) ? Yii::$app->user->identity->street : "",
                ]); ?>
            <?php echo $form->field($model, 'postcode')
                ->textInput([
                    'value' => !empty(Yii::$app->user->identity->postcode) ? Yii::$app->user->identity->postcode : "",
                ]); ?>
            <?php echo $form->field($model, 'city')
                ->textInput([
                    'value' => !empty(Yii::$app->user->identity->city) ? Yii::$app->user->identity->city : "",
                ]); ?>
            <?php echo $form->field($model, 'phone')
                ->textInput([
                    'value' => !empty(Yii::$app->user->identity->phone) ? Yii::$app->user->identity->phone : "",
                ]); ?>
            <?php echo $form->field($model, 'nip')
                ->textInput([
                    'value' => !empty(Yii::$app->user->identity->nip) ? Yii::$app->user->identity->nip : "",
                ]); ?>

            <div class="form-group">
                <?php echo Html::submitButton('Wyślij', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

<?php endif; ?>