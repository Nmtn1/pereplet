<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'О магазине';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>О магазине</span>
    </div>
</div>

<div class="about-page" style="padding: 60px 0;">
    <div class="container">
        <div class="about-content glass" style="padding: 48px; text-align: center; max-width: 900px; margin: 0 auto;">
            <h1 class="about__title" style="font-size: 36px; color: var(--primary); margin-bottom: 30px;">О магазине «Переплёт»</h1>
            
            <div style="margin-bottom: 40px;">
                <img src="<?= Url::to('@web/img/ico/logo.png') ?>" alt="Переплёт" style="max-width: 200px;">
            </div>
            
            <p style="font-size: 18px; line-height: 1.8; margin-bottom: 20px;">
                «Переплёт» — это не просто книжный магазин, а место, где книги обретают своих читателей. 
                Мы собрали для вас лучшие издания: от классической литературы до современных бестселлеров, 
                от детских сказок до научно-популярных изданий.
            </p>
            
            <p style="font-size: 18px; line-height: 1.8; margin-bottom: 20px;">
                Наша миссия — сделать качественную литературу доступной. Мы тщательно отбираем каждую книгу, 
                сотрудничаем только с проверенными издательствами и заботимся о том, чтобы вы получали 
                удовольствие от чтения и от покупки.
            </p>
            
            <p style="font-size: 18px; line-height: 1.8; margin-bottom: 30px;">
                Быстрая доставка, удобный поиск, отзывы читателей и персональные рекомендации — 
                всё для того, чтобы вы нашли именно ту книгу, которая ждёт вас.
            </p>
            
            <div class="advantages" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-top: 50px;">
                <div>
                    <div style="font-size: 40px;"></div>
                    <h3>10 000+ книг</h3>
                    <p>Огромный выбор литературы на любой вкус</p>
                </div>
                <div>
                    <div style="font-size: 40px;"></div>
                    <h3>Бесплатная доставка</h3>
                    <p>При заказе от 1500 ₽</p>
                </div>
                <div>
                    <div style="font-size: 40px;"></div>
                    <h3>Бонусная программа</h3>
                    <p>Копите баллы и экономьте</p>
                </div>
                <div>
                    <div style="font-size: 40px;"></div>
                    <h3>Безопасная оплата</h3>
                    <p>Защита ваших данных</p>
                </div>
            </div>
        </div>
    </div>
</div>