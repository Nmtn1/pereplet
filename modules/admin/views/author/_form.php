<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Достоевский']) ?>
        <?= $form->field($model, 'first_name')->textInput(['placeholder' => 'Фёдор']) ?>
        <?= $form->field($model, 'middle_name')->textInput(['placeholder' => 'Михайлович']) ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'birth_date')->input('date') ?>
        <?= $form->field($model, 'death_date')->input('date') ?>
        
        <div class="form-group">
            <label>Фото автора</label>
            <?php if ($model->photo): ?>
                <div style="margin-bottom: 12px;">
                    <img src="<?= $model->photo ?>" style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="Author[photo_file]" accept="image/*" class="form-control">
            <small>JPG, PNG, WEBP до 5 МБ</small>
        </div>
    </div>
</div>

<?= $form->field($model, 'biography')->textarea(['rows' => 8, 'placeholder' => 'Биография автора...']) ?>

<div style="display: flex; gap: 16px; margin-top: 24px;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn-primary']) ?>
    <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">Отмена</a>
</div>

<?php ActiveForm::end(); ?>