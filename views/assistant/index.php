<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container" style="margin-top: 100px; margin-bottom: 60px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div style="background: white; border-radius: 28px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                <h1 style="color: #7938a4; font-family: 'Playfair Display', serif; font-size: 36px; margin-bottom: 16px;">📚 Лингвистический помощник</h1>
                <p style="font-size: 18px; color: #6c757d; margin-bottom: 32px;">Введите слово или термин — я расскажу, что это значит и как использовать</p>
                
                <?php $form = ActiveForm::begin([
                    'action' => ['assistant/ask'],
                    'method' => 'get',
                    'options' => ['style' => 'display: flex; gap: 12px; justify-content: center;']
                ]); ?>
                    <?= Html::textInput('q', '', [
                        'class' => 'form-control',
                        'style' => 'flex: 1; max-width: 400px; padding: 14px 20px; font-size: 16px; border-radius: 60px; border: 2px solid #e8e7e7;',
                        'placeholder' => 'Например: прокрастинация, мотивация...'
                    ]) ?>
                    <?= Html::submitButton('Спросить', [
                        'class' => 'btn',
                        'style' => 'background: #f17000; color: white; border: none; padding: 14px 32px; border-radius: 60px; font-size: 16px; font-weight: 600; cursor: pointer;'
                    ]) ?>
                <?php ActiveForm::end(); ?>
                
                <hr style="margin: 32px 0 24px;">
                <p style="font-size: 13px; color: #6c757d;">
                    <strong>Примеры запросов:</strong> прокрастинация, мотивация, осознанность
                </p>
                <p style="font-size: 12px; color: #6c757d;">
                    Помощник понимает разные формы слов: "книгами" → "книга", "читаю" → "читать"
                </p>
            </div>
        </div>
    </div>
</div>