<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Заказ #' . $order->id;
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> /
        <a href="<?= Url::to(['/profile/index']) ?>">Личный кабинет</a> /
        <a href="<?= Url::to(['/profile/orders']) ?>">Мои заказы</a> /
        <span>Заказ #<?= $order->id ?></span>
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
                    <a href="<?= Url::to(['/profile/orders']) ?>" class="profile-nav__link active">Мои заказы</a>
                    <a href="<?= Url::to(['/profile/bookmarks']) ?>" class="profile-nav__link">Избранное</a>
                    <a href="<?= Url::to(['/profile/reviews']) ?>" class="profile-nav__link">Мои отзывы</a>
                    <a href="<?= Url::to(['/profile/bonus']) ?>" class="profile-nav__link">Бонусная программа</a>
                </nav>
                <a href="<?= Url::to(['/site/logout']) ?>" class="btn btn--outline logout-btn" data-method="post">Выйти</a>
            </aside>

            <div class="profile-content">
                <div class="profile-card glass">
                    <div class="profile-card__title">
                        Заказ #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?>
                        <span style="float: right; font-size: 14px; font-weight: normal;">
                            от <?= Yii::$app->formatter->asDate($order->created_at, 'dd.MM.yyyy') ?>
                        </span>
                    </div>

                    <div style="background: #f8fafc; padding: 20px; border-radius: 16px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                            <div>
                                <div style="font-size: 13px; color: #64748b;">Статус заказа</div>
                                <div style="font-size: 24px; font-weight: 700;">
                                    <?= $order->getStatusLabel() ?>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 13px; color: #64748b;">Способ оплаты</div>
                                <div style="font-size: 16px;"><?= $order->getPaymentMethodLabel() ?></div>
                            </div>
                            <div>
                                <div style="font-size: 13px; color: #64748b;">Способ доставки</div>
                                <div style="font-size: 16px;"><?= $order->getDeliveryMethodLabel() ?></div>
                            </div>
                            <div>
                                <div style="font-size: 13px; color: #64748b;">Итого</div>
                                <div style="font-size: 28px; font-weight: 700; color: #7938a4;">
                                    <?= number_format($order->total_amount, 0, '', ' ') ?> руб.
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($order->delivery_address): ?>
                    <div style="margin-bottom: 24px;">
                        <h3 style="font-size: 16px; margin-bottom: 8px;">Адрес доставки</h3>
                        <div style="background: #f8fafc; padding: 12px 16px; border-radius: 12px;">
                            <?= Html::encode($order->delivery_address) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <h3 style="font-size: 18px; margin-bottom: 16px;">Товары в заказе</h3>
                    
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Цена</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order->orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; gap: 12px; align-items: center;">
                                            <?php $image = $item->book->getMainImage()->one(); ?>
                                            <img src="<?= $image->image_path ?? '/img/books/default.jpg' ?>" 
                                                 style="width: 50px; height: 65px; object-fit: cover; border-radius: 8px;">
                                            <div>
                                                <a href="<?= Url::to(['/catalog/view', 'slug' => $item->book->slug]) ?>" style="color: #1a1a2e; text-decoration: none;">
                                                    <?= Html::encode($item->book->title) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= number_format($item->price_at_time, 0, '', ' ') ?> руб.</td>
                                    <td><?= $item->quantity ?> шт.</td>
                                    <td><strong><?= number_format($item->price_at_time * $item->quantity, 0, '', ' ') ?> руб.</strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc;">
                                    <td colspan="3" style="text-align: right;"><strong>Итого:</strong></td>
                                    <td><strong style="font-size: 18px; color: #7938a4;"><?= number_format($order->total_amount, 0, '', ' ') ?> руб.</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if ($order->bonus_used > 0 || $order->bonus_earned > 0): ?>
                    <div style="display: flex; gap: 20px; margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 16px;">
                        <?php if ($order->bonus_used > 0): ?>
                        <div>
                            <span style="color: #f17000;">Использовано бонусов:</span>
                            <strong><?= number_format($order->bonus_used, 0, '', ' ') ?> баллов</strong>
                        </div>
                        <?php endif; ?>
                        <?php if ($order->bonus_earned > 0 && $order->status == 'delivered'): ?>
                        <div>
                            <span style="color: #27ae60;">Начислено бонусов:</span>
                            <strong><?= number_format($order->bonus_earned, 0, '', ' ') ?> баллов</strong>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($order->customer_comment): ?>
                    <div style="margin-top: 24px;">
                        <h3 style="font-size: 16px; margin-bottom: 8px;">Ваш комментарий</h3>
                        <div style="background: #f8fafc; padding: 12px 16px; border-radius: 12px;">
                            <?= nl2br(Html::encode($order->customer_comment)) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div style="margin-top: 30px; text-align: center;">
                        <a href="<?= Url::to(['/cart/repeat-order', 'id' => $order->id]) ?>" class="btn btn--primary">Повторить заказ</a>
                        <a href="<?= Url::to(['/profile/orders']) ?>" class="btn btn--outline">Вернуться к списку</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>