<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои заказы';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <a href="<?= Url::to(['/profile/index']) ?>">Личный кабинет</a> / 
        <span>Мои заказы</span>
    </div>
</div>

<div class="orders-page">
    <div class="container">
        <h1 class="orders-title">Мои заказы</h1>
        
        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <div class="empty-state glass">
                    <p>У вас пока нет заказов</p>
                    <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <div class="order-card glass">
                    <div class="order-card__header">
                        <div class="order-card__number">Заказ №<?= $order->id ?></div>
                        <div class="order-card__date"><?= Yii::$app->formatter->asDate($order->created_at) ?></div>
                        <div class="order-status <?= $order->status ?>"><?= $order->getStatusLabel() ?></div>
                    </div>
                    <div class="order-card__products">
                        <?php foreach ($order->orderItems as $item): ?>
                        <div class="order-product-mini">
                            <?php $img = $item->book->getMainImage()->one(); ?>
<img src="<?= $img->image_path ?? '/img/books/default.jpg' ?>" alt="<?= Html::encode($item->book->title) ?>">
                            <div class="order-product-mini__info">
                                <h4><?= Html::encode($item->book->title) ?></h4>
                                <p><?= $item->quantity ?> шт. × <?= number_format($item->price_at_time, 0, '', ' ') ?> руб.</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-card__footer">
                        <div class="order-card__total">Итого: <?= number_format($order->total_amount, 0, '', ' ') ?> руб.</div>
                        <div class="order-card__actions">
                            <a href="<?= Url::to(['/profile/order-view', 'id' => $order->id]) ?>" class="btn btn--outline">Подробнее</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>