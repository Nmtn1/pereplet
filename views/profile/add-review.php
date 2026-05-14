<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Добавить отзыв';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> /
        <a href="<?= Url::to(['/profile/index']) ?>">Личный кабинет</a> /
        <a href="<?= Url::to(['/profile/reviews']) ?>">Мои отзывы</a> /
        <span>Добавить отзыв</span>
    </div>
</div>

<div class="profile-page">
    <div class="container">
        <div class="profile-layout">
            <aside class="profile-sidebar glass">
                <div class="profile-avatar">
                    <div class="avatar-large">
                        <?= strtoupper(substr(Yii::$app->user->identity->first_name ?? 'U', 0, 1)) ?>
                    </div>
                    <h3><?= Html::encode(Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name) ?></h3>
                    <p><?= Html::encode(Yii::$app->user->identity->email) ?></p>
                </div>
                <nav class="profile-nav">
                    <a href="<?= Url::to(['/profile/index']) ?>" class="profile-nav__link">Личные данные</a>
                    <a href="<?= Url::to(['/profile/orders']) ?>" class="profile-nav__link">Мои заказы</a>
                    <a href="<?= Url::to(['/profile/bookmarks']) ?>" class="profile-nav__link">Избранное</a>
                    <a href="<?= Url::to(['/profile/reviews']) ?>" class="profile-nav__link active">Мои отзывы</a>
                    <a href="<?= Url::to(['/profile/bonus']) ?>" class="profile-nav__link">Бонусная программа</a>
                </nav>
                <a href="<?= Url::to(['/site/logout']) ?>" class="btn btn--outline logout-btn" data-method="post">Выйти</a>
            </aside>

            <div class="profile-content">
                <div class="profile-card glass">
                    <div class="profile-card__title">
                        Отзыв на книгу
                        <a href="<?= Url::to(['/profile/reviews']) ?>" style="float: right; font-size: 14px;">Назад</a>
                    </div>

                    <div style="display: flex; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                        <?php $image = $book->getMainImage()->one(); ?>
                        <img src="<?= $image->image_path ?? '/img/books/default.jpg' ?>" style="width: 80px; height: 110px; object-fit: cover; border-radius: 12px;">
                        <div>
                            <h3 style="margin-bottom: 8px;"><?= Html::encode($book->title) ?></h3>
                            <p style="color: #64748b;"><?= $book->author ? Html::encode($book->author->getShortName()) : '' ?></p>
                        </div>
                    </div>

                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form-group">
                        <label>Ваша оценка *</label>
                        <div style="display: flex; gap: 8px; font-size: 32px; cursor: pointer;" id="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span data-rating="<?= $i ?>" style="color: #d1d5db; transition: 0.2s;">★</span>
                            <?php endfor; ?>
                        </div>
                        <?= $form->field($review, 'rating')->hiddenInput()->label(false) ?>
                    </div>

                    <?= $form->field($review, 'title')->textInput(['placeholder' => 'Заголовок отзыва (необязательно)']) ?>
                    <?= $form->field($review, 'comment')->textarea(['rows' => 5, 'placeholder' => 'Расскажите о впечатлениях от книги...']) ?>
                    <?= $form->field($review, 'pros')->textarea(['rows' => 3, 'placeholder' => 'Что вам понравилось?']) ?>
                    <?= $form->field($review, 'cons')->textarea(['rows' => 3, 'placeholder' => 'Что можно улучшить?']) ?>

                    <div style="display: flex; gap: 16px; margin-top: 24px;">
                        <button type="submit" class="btn btn--primary">Отправить отзыв</button>
                        <a href="<?= Url::to(['/profile/reviews']) ?>" class="btn btn--outline">Отмена</a>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#rating-stars span');
    const ratingInput = document.querySelector('#reviews-rating');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#d1d5db';
                }
            });
        });
    });
    if (ratingInput.value) {
        const rating = parseInt(ratingInput.value);
        stars.forEach((s, index) => {
            if (index < rating) {
                s.style.color = '#ffc107';
            }
        });
    }
});
</script>