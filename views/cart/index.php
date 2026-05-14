<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Корзина';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>Корзина</span>
    </div>
</div>

<div class="cart-page">
    <div class="container">
        <h1 class="cart-title">Корзина</h1>
        
        <?php if (empty($items)): ?>
            <div class="empty-cart glass">
                <p>Ваша корзина пуста</p>
                <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary">Перейти в каталог</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <div class="cart-items__header">
                        <div>Товар</div>
                        <div>Цена</div>
                        <div>Количество</div>
                        <div>Итого</div>
                        <div></div>
                    </div>

                    <?php foreach ($items as $item): ?>
                    <?php $book = $item['book']; ?>
                    <div class="cart-item glass" data-id="<?= $book->id ?>">
                        <div class="cart-item__product">
                            <?php $img = $book->getMainImage()->one(); ?>
                            <img src="<?= Url::to('@web/' . ($img->image_path ?? 'img/books/default.jpg')) ?>" alt="<?= Html::encode($book->title) ?>" class="cart-item__img">
                            <div class="cart-item__info">
                                <h3><?= Html::encode($book->title) ?></h3>
                                <p class="cart-item__author"><?= Html::encode($book->author->getShortName() ?? '') ?></p>
                                <div class="cart-item__stock in-stock">В наличии</div>
                            </div>
                        </div>
                        <div class="cart-item__price">
                            <?= number_format($book->price, 0, '', ' ') ?> руб.
                        </div>
                        <div class="cart-item__quantity">
                            <div class="quantity">
                                <button class="quantity__btn minus" data-id="<?= $book->id ?>">-</button>
                                <input type="text" value="<?= $item['quantity'] ?>" class="quantity__input" data-id="<?= $book->id ?>">
                                <button class="quantity__btn plus" data-id="<?= $book->id ?>">+</button>
                            </div>
                        </div>
                        <div class="cart-item__total"><?= number_format($item['subtotal'], 0, '', ' ') ?> руб.</div>
                        <div class="cart-item__remove">
                            <a href="<?= Url::to(['/cart/remove', 'id' => $book->id]) ?>" class="remove-btn">x</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary glass">
                    <h3 class="summary-title">Итого</h3>
                    <div class="summary-row">
                        <span>Товары (<?= $count ?> шт.)</span>
                        <span><?= number_format($total, 0, '', ' ') ?> руб.</span>
                    </div>
                    <div class="summary-row delivery">
                        <span>Доставка</span>
                        <span>Бесплатно</span>
                    </div>
                    <div class="summary-row total">
                        <span>К оплате</span>
                        <span class="total-price"><?= number_format($total, 0, '', ' ') ?> руб.</span>
                    </div>
                    <a href="<?= Url::to(['/cart/checkout']) ?>" class="btn btn--primary btn--full">Перейти к оформлению</a>
                    <a href="<?= Url::to(['/cart/clear']) ?>" class="btn btn--outline btn--full" onclick="return confirm('Очистить корзину?')">Очистить корзину</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.minus, .plus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const input = document.querySelector(`.quantity__input[data-id="${id}"]`);
        let val = parseInt(input.value);
        
        if (this.classList.contains('minus')) {
            if (val > 1) val--;
        } else {
            val++;
        }
        
        window.location.href = '<?= Url::to(['/cart/update']) ?>?id=' + id + '&quantity=' + val;
    });
});

document.querySelectorAll('.quantity__input').forEach(input => {
    input.addEventListener('change', function() {
        const id = this.getAttribute('data-id');
        const val = parseInt(this.value);
        if (val > 0) {
            window.location.href = '<?= Url::to(['/cart/update']) ?>?id=' + id + '&quantity=' + val;
        }
    });
});
</script>