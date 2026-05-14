<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Order $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'guest_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'guest_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bonus_used')->textInput() ?>

    <?= $form->field($model, 'bonus_earned')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'new' => 'New', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled', 'refunded' => 'Refunded', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'payment_method')->dropDownList([ 'card' => 'Card', 'cash' => 'Cash', 'sbp' => 'Sbp', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'payment_status')->dropDownList([ 'pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'delivery_method')->dropDownList([ 'courier' => 'Courier', 'pickup' => 'Pickup', 'mail' => 'Mail', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'delivery_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'customer_comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'manager_comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
