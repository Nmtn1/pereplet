<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-container glass">
            <div class="auth-left">
                <div class="auth-left__content">
                    <h1>Присоединяйтесь!</h1>
                    <p>Создайте аккаунт, чтобы получать персональные скидки, участвовать в акциях и быть в курсе новинок.</p>
                    <div class="auth-features">
                        <div class="auth-feature">✓ Бонусы за регистрацию — 100 ₽</div>
                        <div class="auth-feature">✓ Скидка 10% на первый заказ</div>
                        <div class="auth-feature">✓ Участие в закрытых распродажах</div>
                    </div>
                </div>
            </div>
            <div class="auth-right">
                <h2 class="auth-title">Регистрация</h2>
                <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <?= $form->field($model, 'first_name')->textInput(['class' => 'form-input', 'placeholder' => 'Имя']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'last_name')->textInput(['class' => 'form-input', 'placeholder' => 'Фамилия']) ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'email')->textInput(['class' => 'form-input', 'type' => 'email', 'placeholder' => 'ivan@example.ru']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'phone')->textInput(['class' => 'form-input', 'placeholder' => '+7 (123) 456-78-90']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-input', 'placeholder' => '••••••••']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'form-input', 'placeholder' => '••••••••']) ?>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-agree">
                            <input type="checkbox" required> Я согласен с <a href="#">условиями использования</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn--primary btn--full">Зарегистрироваться</button>
                    
                    <div class="auth-divider"><span>или</span></div>
                    
                    <div class="auth-register">
                        Уже есть аккаунт? <a href="<?= Url::to(['/site/login']) ?>">Войти</a>
                    </div>
                    
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>