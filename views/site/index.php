<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Главная';
?>

<section class="hero">
    <div class="hero__bg"></div>
    <div class="container hero__container">
        <div class="hero__content glass">
            <span class="hero__badge">Книжный интернет-магазин</span>
            <h1 class="hero__title">
                Погрузитесь<br>в чтение <span class="hero__accent">с головой</span>
            </h1>
            <p class="hero__subtitle">Сотни книг на любой вкус. Бестселлеры, новинки, классика — всё в одном месте.</p>
            <div class="search">
                <form action="<?= Url::to(['/catalog/search']) ?>" method="get">
                    <input type="search" name="q" placeholder="Поиск по названию, автору или жанру..." class="search__input">
                    <button type="submit" class="search__btn">
                        <img src="<?= Url::to('@web/img/ico/search.png') ?>" alt="Поиск">
                    </button>
                </form>
            </div>
            <div class="hero__stats">
                <div class="hero__stat">
                    <span class="hero__stat-number">10 000+</span>
                    <span class="hero__stat-label">книг</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">500+</span>
                    <span class="hero__stat-label">авторов</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">50 000+</span>
                    <span class="hero__stat-label">читателей</span>
                </div>
            </div>
        </div>
        <div class="hero__image glass">
            <img src="<?= Url::to('@web/img/books/banner_books.png') ?>" alt="Книги">
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title">Выберите жанр</h2>
            <a href="<?= Url::to(['/catalog/index']) ?>" class="link-more">Все жанры →</a>
        </div>
        <div class="genres-grid">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                <a href="<?= Url::to(['/catalog/index', 'category' => $category->id]) ?>" class="genre-card glass" style="text-decoration: none;">
                    <div class="genre-card__icon"></div>
                    <h3><?= Html::encode($category->name) ?></h3>
                    <p><?= $category->getBookCount() ?> книг</p>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">Категории не найдены</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section section--light">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title">Книги</h2>
            <div class="tabs">
                <button class="tab-btn active" data-tab="tab-popular">Популярное</button>
                <button class="tab-btn" data-tab="tab-new">Новинки</button>
                <button class="tab-btn" data-tab="tab-sales">Скидки</button>
            </div>
        </div>
        
        <div class="tab-content active" id="tab-popular">
            <div class="books-grid">
                <?php if (empty($popular)): ?>
                    <div class="empty-state">Нет популярных книг</div>
                <?php else: ?>
                    <?php foreach ($popular as $book): ?>
                        <div class="book-card glass">
                            <?php if ($book->is_bestseller): ?>
                                <div class="book-card__badge badge-hit">ХИТ</div>
                            <?php endif; ?>
                            <div class="book-card__image">
                                <img src="<?= $book->getImageUrl() ?>" alt="<?= Html::encode($book->title) ?>">
                                <div class="book-card__overlay">
                                    <button class="btn-quick" onclick="location.href='<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>'">Быстрый просмотр</button>
                                </div>
                            </div>
                            <div class="book-card__content">
                                <div class="book-card__price">
                                    <?= number_format($book->getFinalPrice(), 0, '', ' ') ?> руб.
                                    <?php if ($book->getDiscountPercent() > 0): ?>
                                        <span class="old-price"><?= number_format($book->price, 0, '', ' ') ?> руб.</span>
                                    <?php endif; ?>
                                </div>
                                <div class="book-card__rating">
                                    <span class="stars"><?= $book->getStars() ?></span>
                                    <span class="rating-count">(<?= $book->rating_count ?>)</span>
                                </div>
                                <h3 class="book-card__title">
                                    <a href="<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>">
                                        <?= Html::encode($book->title) ?>
                                    </a>
                                </h3>
                                <p class="book-card__author"><?= Html::encode($book->author->getShortName() ?? '') ?></p>
                                <div class="book-card__actions">
                                    <a href="<?= Url::to(['/cart/add', 'id' => $book->id]) ?>" class="btn btn--primary">В корзину</a>
                                    <a href="<?= Url::to(['/profile/add-bookmark', 'id' => $book->id]) ?>" class="btn-icon">
                                        <img src="<?= Url::to('@web/img/ico/bookmark.png') ?>" alt="В избранное">
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="tab-content" id="tab-new">
            <div class="books-grid">
                <?php if (empty($newBooks)): ?>
                    <div class="empty-state">Нет новинок</div>
                <?php else: ?>
                    <?php foreach ($newBooks as $book): ?>
                        <div class="book-card glass">
                            <?php if ($book->is_new): ?>
                                <div class="book-card__badge badge-new">НОВИНКА</div>
                            <?php elseif ($book->is_bestseller): ?>
                                <div class="book-card__badge badge-hit">ХИТ</div>
                            <?php endif; ?>
                            <div class="book-card__image">
                                <img src="<?= $book->getImageUrl() ?>" alt="<?= Html::encode($book->title) ?>">
                                <div class="book-card__overlay">
                                    <button class="btn-quick" onclick="location.href='<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>'">Быстрый просмотр</button>
                                </div>
                            </div>
                            <div class="book-card__content">
                                <div class="book-card__price">
                                    <?= number_format($book->getFinalPrice(), 0, '', ' ') ?> руб.
                                    <?php if ($book->getDiscountPercent() > 0): ?>
                                        <span class="old-price"><?= number_format($book->price, 0, '', ' ') ?> руб.</span>
                                    <?php endif; ?>
                                </div>
                                <div class="book-card__rating">
                                    <span class="stars"><?= $book->getStars() ?></span>
                                    <span class="rating-count">(<?= $book->rating_count ?>)</span>
                                </div>
                                <h3 class="book-card__title">
                                    <a href="<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>">
                                        <?= Html::encode($book->title) ?>
                                    </a>
                                </h3>
                                <p class="book-card__author"><?= Html::encode($book->author->getShortName() ?? '') ?></p>
                                <div class="book-card__actions">
                                    <a href="<?= Url::to(['/cart/add', 'id' => $book->id]) ?>" class="btn btn--primary">В корзину</a>
                                    <a href="<?= Url::to(['/profile/add-bookmark', 'id' => $book->id]) ?>" class="btn-icon">
                                        <img src="<?= Url::to('@web/img/ico/bookmark.png') ?>" alt="В избранное">
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="tab-content" id="tab-sales">
            <div class="books-grid">
                <?php if (empty($saleBooks)): ?>
                    <div class="empty-state">Нет книг со скидкой</div>
                <?php else: ?>
                    <?php foreach ($saleBooks as $book): ?>
                        <div class="book-card glass">
                            <div class="book-card__badge badge-sale">-<?= $book->getDiscountPercent() ?>%</div>
                            <div class="book-card__image">
                                <img src="<?= $book->getImageUrl() ?>" alt="<?= Html::encode($book->title) ?>">
                                <div class="book-card__overlay">
                                    <button class="btn-quick" onclick="location.href='<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>'">Быстрый просмотр</button>
                                </div>
                            </div>
                            <div class="book-card__content">
                                <div class="book-card__price">
                                    <?= number_format($book->getFinalPrice(), 0, '', ' ') ?> руб.
                                    <span class="old-price"><?= number_format($book->price, 0, '', ' ') ?> руб.</span>
                                </div>
                                <div class="book-card__rating">
                                    <span class="stars"><?= $book->getStars() ?></span>
                                    <span class="rating-count">(<?= $book->rating_count ?>)</span>
                                </div>
                                <h3 class="book-card__title">
                                    <a href="<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>">
                                        <?= Html::encode($book->title) ?>
                                    </a>
                                </h3>
                                <p class="book-card__author"><?= Html::encode($book->author->getShortName() ?? '') ?></p>
                                <div class="book-card__actions">
                                    <a href="<?= Url::to(['/cart/add', 'id' => $book->id]) ?>" class="btn btn--primary">В корзину</a>
                                    <a href="<?= Url::to(['/profile/add-bookmark', 'id' => $book->id]) ?>" class="btn-icon">
                                        <img src="<?= Url::to('@web/img/ico/bookmark.png') ?>" alt="В избранное">
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
});
</script>

<style>
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.books-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 25px;
}
.empty-state {
    text-align: center;
    padding: 60px;
    grid-column: 1 / -1;
    color: #64748b;
    font-size: 16px;
}
.tab-btn.active {
    background: #7938a4 !important;
    color: white !important;
}
@media (max-width: 1200px) {
    .books-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width: 992px) {
    .books-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 768px) {
    .books-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}
@media (max-width: 480px) {
    .books-grid {
        grid-template-columns: 1fr;
    }
}
</style>