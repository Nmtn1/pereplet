<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'name')->textInput(['placeholder' => 'Например: АСТ, Эксмо, МИФ']) ?>
        <?= $form->field($model, 'phone')->textInput(['placeholder' => '+7 (495) 123-45-67']) ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'info@publisher.ru']) ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'website')->textInput(['placeholder' => 'https://publisher.ru']) ?>
        <?= $form->field($model, 'address')->textarea(['rows' => 4, 'placeholder' => 'Юридический адрес...']) ?>
    </div>
</div>

<div style="display: flex; gap: 16px; margin-top: 24px;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn-primary']) ?>
    <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">Отмена</a>
</div>

<?php ActiveForm::end(); ?>