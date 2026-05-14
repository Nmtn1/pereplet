<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Оформление заказа';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <a href="<?= Url::to(['/cart/index']) ?>">Корзина</a> / 
        <span>Оформление заказа</span>
    </div>
</div>

<div class="checkout-page">
    <div class="container">
        <h1 class="checkout-title">Оформление заказа</h1>
        
        <div class="checkout-layout">
            <div class="checkout-form">
                <?php $form = ActiveForm::begin(); ?>
                
                <div class="checkout-section glass">
                    <h2 class="checkout-section__title">Контактные данные</h2>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="Order[guest_email]" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label>Телефон *</label>
                                <input type="tel" name="Order[guest_phone]" class="form-input" required>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" value="<?= Yii::$app->user->identity->email ?>" class="form-input" disabled>
                            </div>
                            <div class="form-group">
                                <label>Телефон</label>
                                <input type="tel" value="<?= Yii::$app->user->identity->phone ?>" class="form-input" disabled>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="checkout-section glass">
                    <h2 class="checkout-section__title">Адрес доставки</h2>
                    <?= $form->field($order, 'delivery_address')->textarea(['rows' => 3, 'placeholder' => 'Город, улица, дом, квартира'])->label(false) ?>
                </div>

                <div class="checkout-section glass">
                    <h2 class="checkout-section__title">Способ доставки</h2>
                    <?= $form->field($order, 'delivery_method')->radioList([
                        'courier' => 'Курьерская доставка',
                        'pickup' => 'Пункт выдачи',
                        'mail' => 'Почта России',
                    ], ['itemOptions' => ['class' => 'delivery-option'], 'separator' => ''])->label(false) ?>
                </div>

                <div class="checkout-section glass">
                    <h2 class="checkout-section__title">Способ оплаты</h2>
                    <?= $form->field($order, 'payment_method')->radioList([
                        'card' => 'Картой онлайн',
                        'cash' => 'Наличными при получении',
                        'sbp' => 'СБП',
                    ], ['itemOptions' => ['class' => 'payment-option'], 'separator' => ''])->label(false) ?>
                </div>

                <div class="checkout-section glass">
                    <h2 class="checkout-section__title">Комментарий к заказу</h2>
                    <?= $form->field($order, 'customer_comment')->textarea(['rows' => 3, 'placeholder' => 'Дополнительная информация'])->label(false) ?>
                </div>
            </div>

            <div class="checkout-summary">
                <div class="checkout-summary__card glass">
                    <h3>Ваш заказ</h3>
                    <div class="order-items">
                        <?php foreach ($items as $item): ?>
                        <?php $book = $item['book']; ?>
                        <div class="order-item-mini">
                            <?php $img = $book->getMainImage()->one(); ?>
                            <img src="<?= Url::to('@web/' . ($img->image_path ?? 'img/books/default.jpg')) ?>" alt="<?= Html::encode($book->title) ?>">
                            <div class="order-item-mini__info">
                                <p><?= Html::encode($book->title) ?></p>
                                <span><?= $item['quantity'] ?> шт. × <?= number_format($book->price, 0, '', ' ') ?> руб.</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="summary-row">
                        <span>Товары (<?= $count ?> шт.)</span>
                        <span><?= number_format($total, 0, '', ' ') ?> руб.</span>
                    </div>
                    <div class="summary-row">
                        <span>Доставка</span>
                        <span>Бесплатно</span>
                    </div>
                    <div class="summary-total">
                        <span>Итого</span>
                        <span class="total-price"><?= number_format($total, 0, '', ' ') ?> руб.</span>
                    </div>
                    
                    <button type="submit" class="btn btn--primary btn--full" form="w0">Подтвердить заказ</button>
                </div>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>