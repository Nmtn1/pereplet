<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Личный кабинет';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>Личный кабинет</span>
    </div>
</div>

<div class="profile-page">
    <div class="container">
        <div class="profile-layout">
            <aside class="profile-sidebar glass">
                <div class="profile-avatar">
                    <div class="avatar-large"><?= mb_substr($user->first_name ?: $user->email, 0, 1) ?></div>
                    <h3><?= Html::encode($user->getFullName() ?: $user->email) ?></h3>
                    <p><?= Html::encode($user->email) ?></p>
                </div>
                <nav class="profile-nav">
                    <a href="#" class="profile-nav__link active" data-tab="profile">Личные данные</a>
                    <a href="<?= Url::to(['/profile/orders']) ?>" class="profile-nav__link">Мои заказы</a>
                    <a href="<?= Url::to(['/profile/bookmarks']) ?>" class="profile-nav__link">Избранное</a>
                    <a href="#" class="profile-nav__link" data-tab="security">Безопасность</a>
                    <a href="<?= Url::to(['/profile/reviews']) ?>" class="profile-nav__link">Мои отзывы</a>
                </nav>
                <a href="<?= Url::to(['/site/logout']) ?>" class="btn btn--outline logout-btn" data-method="post">Выйти из аккаунта</a>
            </aside>

            <div class="profile-content">
                <div class="profile-tab active" id="profile">
                    <div class="profile-card glass">
                        <h2 class="profile-card__title">Личные данные</h2>
                        <?php $form = ActiveForm::begin(); ?>
                            <div class="form-row">
                                <div class="form-group">
                                    <?= $form->field($user, 'first_name')->textInput(['class' => 'form-input', 'placeholder' => 'Имя']) ?>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($user, 'last_name')->textInput(['class' => 'form-input', 'placeholder' => 'Фамилия']) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= $form->field($user, 'email')->textInput(['class' => 'form-input', 'type' => 'email']) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($user, 'phone')->textInput(['class' => 'form-input', 'placeholder' => '+7 (123) 456-78-90']) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($user, 'birth_date')->input('date', ['class' => 'form-input']) ?>
                            </div>
                            <button type="submit" class="btn btn--primary">Сохранить изменения</button>
                        <?php ActiveForm::end(); ?>
                    </div>

                    <div class="profile-card glass">
                        <h2 class="profile-card__title">Бонусная программа</h2>
                        <div class="bonus-info">
                            <div class="bonus-balance">
                                <span class="bonus-label">Доступно бонусов:</span>
                                <span class="bonus-value"><?= number_format($user->bonus_points, 0, '', ' ') ?> руб.</span>
                            </div>
                            <p class="bonus-text">1 бонус = 1 рубль. Бонусы начисляются за отзывы и покупки.</p>
                        </div>
                    </div>
                </div>

                <div class="profile-tab" id="security">
                    <div class="profile-card glass">
                        <h2 class="profile-card__title">Смена пароля</h2>
                        <form method="post">
                            <div class="form-group">
                                <label>Текущий пароль</label>
                                <input type="password" name="current_password" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Новый пароль</label>
                                <input type="password" name="password" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Подтверждение пароля</label>
                                <input type="password" name="password_repeat" class="form-input">
                            </div>
                            <button type="submit" class="btn btn--primary">Сменить пароль</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.profile-nav__link[data-tab]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const tabId = link.getAttribute('data-tab');
        document.querySelectorAll('.profile-nav__link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
        link.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    });
});
</script>