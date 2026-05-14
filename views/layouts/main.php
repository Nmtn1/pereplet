<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Cart;

$cartCount = Cart::getCount();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | Переплёт</title>
    <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?= Html::cssFile('@web/css/style.css') ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="page">
    <header class="header">
        <div class="container header__container">
            <a href="<?= Url::home() ?>" class="logo">
                <img src="<?= Url::to('@web/img/ico/logo.png') ?>" alt="Переплёт">
            </a>
            <nav class="nav">
                <a href="<?= Url::to(['/catalog/index']) ?>" class="nav__link">Каталог</a>
                <a href="<?= Url::to(['/site/promotions']) ?>" class="nav__link">Акции</a>
                <a href="<?= Url::to(['/site/sales']) ?>" class="nav__link">Распродажа</a>
                <a href="<?= Url::to(['/site/about']) ?>" class="nav__link">О нас</a>
                <a href="<?= Url::to(['/assistant/index']) ?>" class="nav__link">Лингвистический помощник</a>
                
            </nav>
            <div class="header__actions">
                <div class="dropdown">
                    <button class="dropdown__btn">
                        <img src="<?= Url::to('@web/img/ico/user.png') ?>" alt="Профиль">
                    </button>
                    <div class="dropdown__content">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a href="<?= Url::to(['/site/login']) ?>">Вход</a>
                            <a href="<?= Url::to(['/site/register']) ?>">Регистрация</a>
                        <?php else: ?>
                            <a href="<?= Url::to(['/profile/index']) ?>">Настройки профиля</a>
                            <a href="<?= Url::to(['/profile/orders']) ?>">Мои заказы</a>
                            <?php if (Yii::$app->user->identity->isAdmin()): ?>
                                <a href="<?= Url::to(['/admin/default/index']) ?>">Админка</a>
                            <?php endif; ?>
                            <a href="<?= Url::to(['/site/logout']) ?>" data-method="post">Выйти</a>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= Url::to(['/profile/bookmarks']) ?>" class="header__action">
                    <img src="<?= Url::to('@web/img/ico/bookmark_main.png') ?>" alt="Избранное">
                </a>
                <a href="<?= Url::to(['/cart/index']) ?>" class="header__action">
                    <img src="<?= Url::to('@web/img/ico/cart.png') ?>" alt="Корзина">
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-count"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="container footer__container">
            <div class="footer__col">
                <h3>Мы в социальных сетях</h3>
                <a href="#">Вконтакте</a>
                <a href="#">Телеграм</a>
                <a href="#">Одноклассники</a>
                <a href="#">Tiktok</a>
            </div>
            <div class="footer__col">
                <h3>Товары</h3>
                <a href="<?= Url::to(['/catalog/index']) ?>">Книги</a>
                <a href="<?= Url::to(['/site/promotions']) ?>">Акции</a>
                <a href="<?= Url::to(['/site/sales']) ?>">Распродажа</a>
            </div>
            <div class="footer__col">
                <h3>О магазине</h3>
                <a href="tel:+71234567890">+7 123 456 78 90</a>
                <a href="#">О нас</a>
                <a href="#">Контакты</a>
            </div>
            <div class="footer__col">
                <h3>Покупателям</h3>
                <a href="#">Доставка и оплата</a>
                <a href="#">Возврат</a>
                <a href="#">Политика конфиденциальности</a>
            </div>
        </div>
        <div class="footer__copyright">
            <div class="container">
                <p>Переплёт © 2025. Все права защищены.</p>
            </div>
        </div>
    </footer>
</div>

<style>
.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #f17000;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 50%;
}
.header__action {
    position: relative;
}
</style>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>