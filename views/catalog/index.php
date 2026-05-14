<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = $searchQuery ? "Поиск: {$searchQuery}" : ($categoryName ?? 'Каталог книг');
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span><?= Html::encode($this->title) ?></span>
    </div>
</div>

<div class="catalog-page">
    <div class="container">
        <div class="catalog-search-bar" style="margin-bottom: 30px;">
            <form action="<?= Url::to(['/catalog/index']) ?>" method="get" class="search-form" style="display: flex; gap: 12px; max-width: 600px; margin: 0 auto;">
                <input type="text" 
                       name="q" 
                       value="<?= Html::encode($searchQuery) ?>" 
                       placeholder="Поиск по названию, автору или жанру..." 
                       style="flex: 1; padding: 14px 20px; border: 2px solid #e2e8f0; border-radius: 60px; outline: none; font-size: 16px;">
                <button type="submit" style="background: #7938a4; border: none; border-radius: 60px; padding: 0 28px; color: white; font-weight: 600; cursor: pointer;">
                    Найти
                </button>
            </form>
            <?php if ($searchQuery): ?>
                <div style="text-align: center; margin-top: 12px;">
                    <span style="background: #f0e6f5; padding: 6px 16px; border-radius: 30px; font-size: 14px;">
                        Результаты поиска: «<?= Html::encode($searchQuery) ?>»
                        <a href="<?= Url::to(['/catalog/index']) ?>" style="margin-left: 8px; color: #dc2626;">✕</a>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="catalog-layout">
            <aside class="catalog-sidebar glass">
                <h3 class="sidebar__title">Фильтры</h3>
                
                <div class="filter-group">
                    <h4>Жанры</h4>
                    <?php foreach ($categories as $cat): ?>
                    <label class="checkbox">
                        <input type="checkbox" name="category" value="<?= $cat->id ?>" 
                            <?= ($currentCategory == $cat->id) ? 'checked' : '' ?>
                            onchange="applyFilters()">
                        <?= Html::encode($cat->name) ?>
                        <span class="count">(<?= $cat->getBooks()->count() ?>)</span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <div class="filter-group">
                    <h4>Цена</h4>
                    <div class="price-range">
                        <input type="number" placeholder="от" class="price-input" id="price-min" value="<?= Yii::$app->request->get('price_min') ?>">
                        <span>—</span>
                        <input type="number" placeholder="до" class="price-input" id="price-max" value="<?= Yii::$app->request->get('price_max') ?>">
                    </div>
                    <button class="btn btn--outline filter-reset" onclick="applyPriceFilter()" style="margin-top: 12px;">Применить</button>
                </div>

                <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--outline filter-reset">Сбросить фильтры</a>
            </aside>

            <div class="catalog-content">
                <div class="catalog-header">
                    <h1 class="catalog-title"><?= Html::encode($this->title) ?></h1>
                    <div class="catalog-sort">
                        <span>Сортировать:</span>
                        <select class="sort-select" id="sort-select">
                            <option value="popular" <?= $sort == 'popular' ? 'selected' : '' ?>>По популярности</option>
                            <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>По цене (возр.)</option>
                            <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>По цене (убыв.)</option>
                            <option value="new" <?= $sort == 'new' ? 'selected' : '' ?>>По новизне</option>
                            <option value="title" <?= $sort == 'title' ? 'selected' : '' ?>>По названию</option>
                        </select>
                    </div>
                </div>

                <div class="catalog-stats">
                    Найдено <strong><?= $pagination->totalCount ?></strong> книг
                </div>

                <div class="books-grid catalog-grid">
                    <?php foreach ($books as $book): ?>
                    <div class="book-card glass">
                        <?php if ($book->is_bestseller): ?>
                            <div class="book-card__badge badge-hit">ХИТ</div>
                        <?php elseif ($book->is_new): ?>
                            <div class="book-card__badge badge-new">NEW</div>
                        <?php elseif ($book->getDiscountPercent() > 0): ?>
                            <div class="book-card__badge badge-sale">-<?= $book->getDiscountPercent() ?>%</div>
                        <?php endif; ?>
                        
                        <a href="<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>" class="book-card__image-link">
                            <div class="book-card__image">
                                <img src="<?= $book->getImageUrl() ?>" alt="<?= Html::encode($book->title) ?>">
                            </div>
                        </a>
                        
                        <div class="book-card__content">
                            <div class="book-card__price">
                                <?php if ($book->old_price > $book->price): ?>
                                    <span class="old-price"><?= number_format($book->old_price, 0, '', ' ') ?> ₽</span>
                                <?php endif; ?>
                                <?= number_format($book->getFinalPrice(), 0, '', ' ') ?> ₽
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
                            <p class="book-card__author">
                                <?= $book->author ? Html::encode($book->author->getShortName()) : '' ?>
                            </p>
                            <div class="book-card__actions">
                                <a href="<?= Url::to(['/cart/add', 'id' => $book->id]) ?>" class="btn btn--primary">В корзину</a>
                                <a href="<?= Url::to(['/profile/add-bookmark', 'id' => $book->id]) ?>" class="btn-icon">
                                    <img src="/img/ico/bookmark.png" alt="В избранное">
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($books)): ?>
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <p>Книги не найдены</p>
                        <p style="color: #64748b; margin-top: 8px;">Попробуйте изменить поисковый запрос или сбросить фильтры</p>
                        <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary">Сбросить фильтры</a>
                    </div>
                    <?php endif; ?>
                </div>

                <?= LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => ['class' => 'pagination'],
                    'linkOptions' => ['class' => 'pagination__num'],
                    'prevPageLabel' => '←',
                    'nextPageLabel' => '→',
                    'activePageCssClass' => 'active',
                    'disableCurrentPageButton' => true,
                    'hideOnSinglePage' => true,
                ]) ?>
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    let params = new URLSearchParams(window.location.search);
    

    let selectedCategory = document.querySelector('input[name="category"]:checked');
    if (selectedCategory) {
        params.set('category', selectedCategory.value);
    } else {
        params.delete('category');
    }
    let priceMin = document.getElementById('price-min').value;
    let priceMax = document.getElementById('price-max').value;
    if (priceMin) params.set('price_min', priceMin);
    else params.delete('price_min');
    if (priceMax) params.set('price_max', priceMax);
    else params.delete('price_max');
    let sort = document.getElementById('sort-select').value;
    params.set('sort', sort);

    let searchQuery = '<?= Html::encode($searchQuery) ?>';
    if (searchQuery) {
        params.set('q', searchQuery);
    }
    
    window.location.href = '<?= Url::to(['/catalog/index']) ?>?' + params.toString();
}

function applyPriceFilter() {
    applyFilters();
}

document.getElementById('sort-select').addEventListener('change', applyFilters);
</script>