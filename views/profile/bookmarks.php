<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Избранное';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>Избранное</span>
    </div>
</div>

<div class="bookmarks-page">
    <div class="container">
        <div class="bookmarks-header">
            <h1 class="bookmarks-title">Избранное</h1>
            <a href="<?= Url::to(['/profile/clear-bookmarks']) ?>" class="clear-all-btn" onclick="return confirm('Очистить всё избранное?')">Очистить всё</a>
        </div>

        <div class="books-grid bookmarks-grid">
            <?php if (empty($bookmarks)): ?>
                <div class="empty-state glass">
                    <div class="empty-state__icon"></div>
                    <h3>Избранное пусто</h3>
                    <p>Добавляйте книги в избранное, чтобы не потерять понравившиеся</p>
                    <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>
            <?php else: ?>
                <?php foreach ($bookmarks as $bookmark): ?>
                <?php $book = $bookmark->book; ?>
                <div class="book-card glass">
                    <?php if ($book->is_bestseller): ?>
                        <div class="book-card__badge badge-hit">ХИТ</div>
                    <?php elseif ($book->is_new): ?>
                        <div class="book-card__badge badge-new">NEW</div>
                    <?php endif; ?>
                    <div class="book-card__image">
                        <?php $img = $book->getMainImage()->one(); ?>
                        <img src="<?= Url::to('@web/' . ($img->image_path ?? 'img/books/default.jpg')) ?>" alt="<?= Html::encode($book->title) ?>">
                    </div>
                    <div class="book-card__content">
                        <div class="book-card__price"><?= number_format($book->price, 0, '', ' ') ?> руб.</div>
                        <h3 class="book-card__title"><?= Html::encode($book->title) ?></h3>
                        <p class="book-card__author"><?= Html::encode($book->author->getShortName() ?? '') ?></p>
                        <div class="book-card__actions">
                            <a href="<?= Url::to(['/cart/add', 'id' => $book->id]) ?>" class="btn btn--primary">В корзину</a>
                            <a href="<?= Url::to(['/profile/remove-bookmark', 'id' => $book->id]) ?>" class="btn-icon remove-fav">
                                <img src="<?= Url::to('@web/img/ico/delete.png') ?>" alt="Удалить">
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>