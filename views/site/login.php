<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Вход';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-container glass">
            <div class="auth-left">
                <div class="auth-left__content">
                    <h1>Добро пожаловать!</h1>
                    <p>Войдите в свой аккаунт, чтобы продолжить покупки, отслеживать заказы и получать персональные рекомендации.</p>
                    <div class="auth-features">
                        <div class="auth-feature">✓ Быстрый заказ в 1 клик</div>
                        <div class="auth-feature">✓ Накопление бонусов</div>
                        <div class="auth-feature">✓ Персональные скидки</div>
                    </div>
                </div>
            </div>
            <div class="auth-right">
                <h2 class="auth-title">Вход в аккаунт</h2>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'email')->textInput(['class' => 'form-input', 'placeholder' => 'example@mail.ru'])->label('Email') ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-input', 'placeholder' => '••••••••'])->label('Пароль') ?>
                    </div>
                    
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'class' => 'checkbox-agree',
                        'template' => "<div class=\"checkbox-wrapper\">{input} {label}</div>\n{error}",
                    ]) ?>
                    
                    <button type="submit" class="btn btn--primary btn--full">Войти</button>
                    
                    <div class="auth-divider"><span>или</span></div>
                    
                    <div class="auth-register">
                        Нет аккаунта? <a href="<?= Url::to(['/site/register']) ?>">Зарегистрироваться</a>
                    </div>
                    
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>