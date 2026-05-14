<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Акции и предложения';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>Акции</span>
    </div>
</div>

<div class="promotions-page">
    <div class="container">
        <h1 class="promotions-title">Акции и предложения</h1>
        
        <?php if ($mainPromotion): ?>
        <div class="promo-banner glass">
            <div class="promo-banner__content">
                <span class="promo-banner__tag">Спецпредложение</span>
                <h2><?= Html::encode($mainPromotion->name) ?></h2>
                <p><?= Html::encode($mainPromotion->description) ?></p>
                <p>
                    <strong>
                        <?php if ($mainPromotion->discount_type == 'percent'): ?>
                            Скидка <?= $mainPromotion->discount_value ?>% 
                            <?php if ($mainPromotion->min_order_amount): ?>
                                при заказе от <?= number_format($mainPromotion->min_order_amount, 0, '', ' ') ?> руб.
                            <?php endif; ?>
                        <?php else: ?>
                            Скидка <?= number_format($mainPromotion->discount_value, 0, '', ' ') ?> руб.
                            <?php if ($mainPromotion->min_order_amount): ?>
                                при заказе от <?= number_format($mainPromotion->min_order_amount, 0, '', ' ') ?> руб.
                            <?php endif; ?>
                        <?php endif; ?>
                    </strong>
                </p>
                <?php if ($mainPromotion->code): ?>
                    <p>Промокод: <strong style="background: white; padding: 4px 12px; border-radius: 20px;"><?= $mainPromotion->code ?></strong></p>
                <?php endif; ?>
                <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary">Выбрать книги</a>
            </div>
            <div class="promo-banner__image">
                <span class="promo-icon"></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($promotions)): ?>
            <div class="empty-state" style="text-align: center; padding: 60px; background: white; border-radius: 24px;">
                <p style="font-size: 48px; margin-bottom: 16px;"></p>
                <p style="font-size: 18px; color: #64748b;">Акций пока нет</p>
                <p style="color: #9ca3af; margin-top: 8px;">Загляните позже — появятся новые предложения!</p>
            </div>
        <?php else: ?>
            <div class="promotions-grid">
                <?php foreach ($promotions as $promo): ?>
                    <div class="promo-card glass">
                        <div class="promo-card__badge">
                            <?php if ($promo->discount_type == 'percent'): ?>
                                -<?= $promo->discount_value ?>%
                            <?php else: ?>
                                -<?= number_format($promo->discount_value, 0, '', ' ') ?> руб.
                            <?php endif; ?>
                        </div>
                        <div class="promo-card__image">
                            <img src="<?= Url::to('@web/img/books/default.jpg') ?>" alt="<?= Html::encode($promo->name) ?>">
                        </div>
                        <div class="promo-card__content">
                            <h3><?= Html::encode($promo->name) ?></h3>
                            <p><?= Html::encode($promo->description) ?></p>
                            
                            <?php if ($promo->min_order_amount): ?>
                                <div style="font-size: 12px; color: #f17000; margin: 8px 0;">
                                    Минимальная сумма: <?= number_format($promo->min_order_amount, 0, '', ' ') ?> руб.
                                </div>
                            <?php endif; ?>
                            
                            <div class="promo-card__date">
                                <?php if ($promo->end_date): ?>
                                    До <?= Yii::$app->formatter->asDate($promo->end_date, 'dd MMMM yyyy') ?>
                                <?php else: ?>
                                    Без срока
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($promo->code): ?>
                                <div class="promo-code" style="margin: 10px 0;">
                                    <span style="background: #f3e8ff; padding: 4px 10px; border-radius: 20px; font-size: 12px;">
                                        Промокод: <?= $promo->code ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary btn--small">Смотреть</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="bonus-section glass">
            <div class="bonus-section__icon">⭐</div>
            <div class="bonus-section__content">
                <h2>Бонусная программа Переплёт+</h2>
                <p>За каждую покупку вы получаете бонусы, которые можно использовать для оплаты следующих заказов.</p>
                <div class="bonus-levels">
                    <div class="bonus-level">
                        <span class="level-name">Новичок</span>
                        <span class="level-bonus">1% бонусами</span>
                    </div>
                    <div class="bonus-level">
                        <span class="level-name">Читатель</span>
                        <span class="level-bonus">3% бонусами</span>
                    </div>
                    <div class="bonus-level">
                        <span class="level-name">Книголюб</span>
                        <span class="level-bonus">5% бонусами</span>
                    </div>
                    <div class="bonus-level">
                        <span class="level-name">Эксперт</span>
                        <span class="level-bonus">7% бонусами</span>
                    </div>
                </div>
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/site/register']) ?>" class="btn btn--outline">Зарегистрироваться</a>
                <?php else: ?>
                    <div class="user-bonus" style="margin-top: 16px;">
                        <span style="background: #f3e8ff; padding: 8px 16px; border-radius: 30px;">
                            Ваши бонусы: <strong><?= number_format(Yii::$app->user->identity->bonus_points, 0, '', ' ') ?> руб.</strong>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.promo-code {
    margin: 10px 0;
}
.promo-card__badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: linear-gradient(135deg, #f17000, #d45c00);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    z-index: 2;
}
.user-bonus {
    text-align: center;
}
.empty-state {
    margin: 40px 0;
}
</style>