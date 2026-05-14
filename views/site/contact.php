<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Контакты';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>Контакты</span>
    </div>
</div>

<div class="contacts-page" style="padding: 60px 0;">
    <div class="container">
        <div class="contact-layout" style="display: flex; gap: 40px; flex-wrap: wrap;">
            <div class="contact-info glass" style="flex: 1; padding: 32px;">
                <h2>Наши контакты</h2>
                <div style="margin: 20px 0;">
                    <p><strong>Телефон:</strong> <a href="tel:+71234567890">+7 (123) 456-78-90</a></p>
                    <p><strong>Email:</strong> <a href="mailto:info@pereplet.ru">info@pereplet.ru</a></p>
                    <p><strong>Адрес:</strong> г. Москва, ул. Книжная, д. 10</p>
                    <p><strong>Режим работы:</strong> Пн-Пт: 10:00 - 20:00, Сб-Вс: 11:00 - 18:00</p>
                </div>
                <div class="social-links" style="margin-top: 30px;">
                    <h3>Мы в соцсетях:</h3>
                    <div style="display: flex; gap: 15px; margin-top: 15px;">
                        <a href="#">ВКонтакте</a>
                        <a href="#">Telegram</a>
                        <a href="#">Одноклассники</a>
                    </div>
                </div>
            </div>
            
            <div class="contact-form glass" style="flex: 1; padding: 32px;">
                <h2>Напишите нам</h2>
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Ваше имя']) ?>
                    <?= $form->field($model, 'email')->textInput(['type' => 'email', 'placeholder' => 'Ваш email']) ?>
                    <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Тема сообщения']) ?>
                    <?= $form->field($model, 'body')->textarea(['rows' => 5, 'placeholder' => 'Ваше сообщение']) ?>
                    <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::class) ?>
                    <button type="submit" class="btn btn--primary">Отправить</button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>