<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Bookmark;

$this->title = $book->title;
$isBookmarked = Bookmark::isBookmarked($book->id);

$existingReview = null;
if (!Yii::$app->user->isGuest) {
    $existingReview = \app\models\Reviews::find()
        ->where(['user_id' => Yii::$app->user->id, 'book_id' => $book->id])
        ->one();
}
?>


<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <a href="<?= Url::to(['/catalog/index']) ?>">Каталог</a> / 
        <span><?= Html::encode($book->title) ?></span>
    </div>
</div>

<div class="product-page">
    <div class="container">
        <div class="product-layout">
            <div class="product-gallery">
                <div class="product-gallery__main glass">
                    <img src="<?= $book->getImageUrl() ?>" alt="<?= Html::encode($book->title) ?>" id="mainImage">
                </div>
                <div class="product-gallery__thumbs">
                    <?php foreach ($book->getImages()->all() as $img): ?>
                    <div class="thumb" data-img="<?= $img->image_path ?>">
                        <img src="<?= $img->image_path ?>" alt="<?= Html::encode($book->title) ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="product-info glass">
                <?php if ($book->is_bestseller): ?>
                    <div class="product-badge">Бестселлер</div>
                <?php endif; ?>
                
                <h1 class="product-title"><?= Html::encode($book->title) ?></h1>
                <p class="product-author"><?= Html::encode($book->author->getFullName() ?? '') ?></p>
                
                <div class="product-rating">
                    <div class="stars"><?= $book->getStars() ?></div>
                    <span class="rating-value"><?= $book->getRatingAverage() ?></span>
                    <span class="rating-count">(<?= $book->rating_count ?> отзывов)</span>
                </div>

                <div class="product-price">
                    <?php if ($book->old_price): ?>
                        <span class="old-price"><?= number_format($book->old_price, 0, '', ' ') ?> ₽</span>
                    <?php endif; ?>
                    <span class="current-price"><?= number_format($book->getFinalPrice(), 0, '', ' ') ?> ₽</span>
                </div>

                <div class="product-stock <?= $book->stock > 0 ? 'in-stock' : 'out-stock' ?>">
                    <?= $book->stock > 0 ? '✓ В наличии (' . $book->stock . ' шт.)' : '✗ Нет в наличии' ?>
                </div>

                <div class="product-actions">
                    <div class="quantity">
                        <button class="quantity__btn minus">-</button>
                        <input type="text" value="1" class="quantity__input" id="quantity">
                        <button class="quantity__btn plus">+</button>
                    </div>
                    <?php if ($book->stock > 0): ?>
                        <a href="#" class="btn btn--primary btn--large add-to-cart" data-id="<?= $book->id ?>">В корзину</a>
                    <?php else: ?>
                        <button class="btn btn--outline btn--large" disabled>Нет в наличии</button>
                    <?php endif; ?>
                    <a href="<?= Url::to(['/profile/add-bookmark', 'id' => $book->id]) ?>" class="btn-icon btn--large-icon">
                        <img src="<?= Url::to('@web/img/ico/bookmark.png') ?>" alt="В избранное">
                    </a>
                </div>

                <div class="product-meta">
                    <div class="meta-row">
                        <span class="meta-label">ISBN:</span>
                        <span><?= $book->isbn ?? '—' ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Издательство:</span>
                        <span><?= Html::encode($book->publisher->name ?? '—') ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Год издания:</span>
                        <span><?= $book->year ?? '—' ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Страниц:</span>
                        <span><?= $book->pages ?? '—' ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Переплёт:</span>
                        <span><?= $book->cover_type ?? '—' ?></span>
                    </div>
                </div>

                <div class="product-genres">
                    <?php 
                    $categories = $book->getCategories()->all();
                    if (!empty($categories)):
                        foreach ($categories as $cat):
                    ?>
                        <span class="genre-tag"><?= Html::encode($cat->name) ?></span>
                    <?php 
                        endforeach;
                    else:
                    ?>
                        <span class="genre-tag"><?= Html::encode($book->mainCategory->name ?? 'Без категории') ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="product-tabs">
            <div class="tabs product-tabs__header">
                <button class="tab-btn active" data-tab="desc">Описание</button>
                <button class="tab-btn" data-tab="chars">Характеристики</button>
                <button class="tab-btn" data-tab="reviews">Отзывы (<?= count($book->reviews) ?>)</button>
            </div>

            <div class="tab-content active" id="desc">
                <div class="product-description glass">
                    <p><?= nl2br(Html::encode($book->description)) ?></p>
                </div>
            </div>

            <div class="tab-content" id="chars">
                <div class="product-chars glass">
                    <div class="chars-row"><span class="chars-label">Автор:</span><span><?= Html::encode($book->author->getFullName() ?? '—') ?></span></div>
                    <div class="chars-row"><span class="chars-label">Издательство:</span><span><?= Html::encode($book->publisher->name ?? '—') ?></span></div>
                    <div class="chars-row"><span class="chars-label">Год:</span><span><?= $book->year ?? '—' ?></span></div>
                    <div class="chars-row"><span class="chars-label">Страниц:</span><span><?= $book->pages ?? '—' ?></span></div>
                    <div class="chars-row"><span class="chars-label">Переплёт:</span><span><?= $book->cover_type ?? '—' ?></span></div>
                    <div class="chars-row"><span class="chars-label">Формат:</span><span><?= $book->format ?? '—' ?></span></div>
                    <div class="chars-row"><span class="chars-label">Вес:</span><span><?= $book->weight ? $book->weight . ' г' : '—' ?></span></div>
                    <div class="chars-row"><span class="chars-label">ISBN:</span><span><?= $book->isbn ?? '—' ?></span></div>
                </div>
            </div>

            <div class="tab-content" id="reviews">
                <div class="product-reviews">
                    <div class="reviews-summary glass">
                        <div class="reviews-summary__rating">
                            <div class="big-rating"><?= $book->getRatingAverage() ?></div>
                            <div class="stars big-stars"><?= $book->getStars() ?></div>
                            <div class="reviews-count">На основе <?= $book->rating_count ?> отзывов</div>
                        </div>
                        <?php if (!Yii::$app->user->isGuest): ?>
    <?php if ($existingReview): ?>
        <button class="btn btn--outline write-review-btn" onclick="showReviewForm()">
            Редактировать отзыв
        </button>
    <?php else: ?>
        <button class="btn btn--outline write-review-btn" onclick="showReviewForm()">
            Написать отзыв
        </button>
    <?php endif; ?>
<?php else: ?>
    <a href="<?= Url::to(['/site/login']) ?>" class="btn btn--outline">Войдите, чтобы оставить отзыв</a>
<?php endif; ?>
                    </div>


<?php

$existingReview = null;
if (!Yii::$app->user->isGuest) {
    $existingReview = \app\models\Reviews::find()
        ->where(['user_id' => Yii::$app->user->id, 'book_id' => $book->id])
        ->one();
}
?>

<div id="review-form-container" style="display: none; margin-bottom: 30px;">
    <div class="review-card glass">
        <h3 style="margin-bottom: 20px;">
            <?= $existingReview ? 'Редактировать отзыв' : 'Оставить отзыв' ?>
        </h3>
        
        <?php $form = ActiveForm::begin(['action' => Url::to(['/catalog/add-review', 'book_id' => $book->id]), 'method' => 'post']); ?>
            
            <div class="form-group">
                <label>Ваша оценка *</label>
                <div style="display: flex; gap: 8px; font-size: 32px; cursor: pointer;" id="rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span data-rating="<?= $i ?>" style="color: #d1d5db; transition: 0.2s;">
                            ★
                        </span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="Reviews[rating]" id="rating-value" 
                       value="<?= $existingReview ? $existingReview->rating : '' ?>">
            </div>
            
            <div class="form-group">
                <label>Заголовок (необязательно)</label>
                <input type="text" name="Reviews[title]" class="form-input" 
                       value="<?= $existingReview ? Html::encode($existingReview->title) : '' ?>"
                       placeholder="Краткий заголовок отзыва">
            </div>
            
            <div class="form-group">
                <label>Комментарий *</label>
                <textarea name="Reviews[comment]" rows="5" class="form-input" 
                          placeholder="Расскажите о впечатлениях от книги..." required><?= $existingReview ? Html::encode($existingReview->comment) : '' ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Достоинства (необязательно)</label>
                <textarea name="Reviews[pros]" rows="2" class="form-input" 
                          placeholder="Что вам понравилось?"><?= $existingReview ? Html::encode($existingReview->pros) : '' ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Недостатки (необязательно)</label>
                <textarea name="Reviews[cons]" rows="2" class="form-input" 
                          placeholder="Что можно улучшить?"><?= $existingReview ? Html::encode($existingReview->cons) : '' ?></textarea>
            </div>
            
            <button type="submit" class="btn btn--primary">
                <?= $existingReview ? 'Обновить отзыв' : 'Отправить отзыв' ?>
            </button>
            <button type="button" class="btn btn--outline" onclick="hideReviewForm()">Отмена</button>
            
        <?php ActiveForm::end(); ?>
    </div>
</div>

                    <div class="reviews-list">
                        <?php if (empty($book->reviews)): ?>
                            <div class="empty-state glass" style="text-align: center; padding: 40px;">
                                <p>Будьте первым, кто оставит отзыв</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($book->reviews as $review): ?>
                            <div class="review-card glass">
                                <div class="review-header">
                                    <div class="review-author">
                                        <div class="author-avatar"><?= mb_substr($review->user->first_name ?? $review->user->email, 0, 1) ?></div>
                                        <div class="author-info">
                                            <h4><?= Html::encode($review->user->getFullName() ?: $review->user->email) ?></h4>
                                            <span class="review-date"><?= Yii::$app->formatter->asDate($review->created_at) ?></span>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <div class="stars"><?= str_repeat('★', $review->rating) ?><?= str_repeat('☆', 5 - $review->rating) ?></div>
                                    </div>
                                </div>
                                <?php if ($review->title): ?>
                                    <div style="font-weight: 600; margin-bottom: 8px;"><?= Html::encode($review->title) ?></div>
                                <?php endif; ?>
                                <div class="review-content">
                                    <p><?= nl2br(Html::encode($review->comment)) ?></p>
                                </div>
                                <?php if ($review->pros): ?>
                                    <div style="margin-top: 12px; color: #27ae60; font-size: 13px;">
                                        Достоинства: <?= Html::encode($review->pros) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($review->cons): ?>
                                    <div style="margin-top: 8px; color: #dc2626; font-size: 13px;">
                                        Недостатки: <?= Html::encode($review->cons) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($related)): ?>
<div class="recommended">
    <h3>Похожие книги</h3>
    <div class="books-grid">
        <?php foreach ($related as $rel): ?>
        <div class="book-card glass">
            <a href="<?= Url::to(['/catalog/view', 'slug' => $rel->slug]) ?>" style="text-decoration: none; color: inherit;">
                <div class="book-card__image">
                    <img src="<?= $rel->getImageUrl() ?>" alt="<?= Html::encode($rel->title) ?>">
                </div>
                <div class="book-card__content">
                    <div class="book-card__price"><?= number_format($rel->getFinalPrice(), 0, '', ' ') ?> руб.</div>
                    <h3 class="book-card__title"><?= Html::encode($rel->title) ?></h3>
                    <p class="book-card__author"><?= Html::encode($rel->author->getShortName() ?? '') ?></p>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tabId = btn.getAttribute('data-tab');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    });
});

document.querySelectorAll('.thumb').forEach(thumb => {
    thumb.addEventListener('click', () => {
        document.getElementById('mainImage').src = thumb.getAttribute('data-img');
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    });
});

const minus = document.querySelector('.minus');
const plus = document.querySelector('.plus');
const quantityInput = document.querySelector('#quantity');

if (minus && plus && quantityInput) {
    minus.addEventListener('click', () => {
        let val = parseInt(quantityInput.value);
        if (val > 1) quantityInput.value = val - 1;
    });
    plus.addEventListener('click', () => {
        let val = parseInt(quantityInput.value);
        quantityInput.value = val + 1;
    });
}
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const id = btn.getAttribute('data-id');
        const quantity = document.querySelector('#quantity')?.value || 1;
        window.location.href = '<?= Url::to(['/cart/add']) ?>/id/' + id + '?quantity=' + quantity;
    });
});
function showReviewForm() {
    document.getElementById('review-form-container').style.display = 'block';
    document.querySelector('.write-review-btn').style.display = 'none';
}

function hideReviewForm() {
    document.getElementById('review-form-container').style.display = 'none';
    document.querySelector('.write-review-btn').style.display = 'inline-block';
}

document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#rating-stars span');
    const ratingInput = document.getElementById('rating-value');
    
    const existingRating = ratingInput.value;
    if (existingRating) {
        stars.forEach((s, index) => {
            if (index < existingRating) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#d1d5db';
            }
        });
    }
    
    if (stars.length && ratingInput) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });
        });
    }
});
</script>

<style>
.empty-state {
    text-align: center;
    padding: 40px;
}

.reviews-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 24px;
    flex-wrap: wrap;
    gap: 20px;
}

.big-rating {
    font-size: 48px;
    font-weight: 700;
    color: #7938a4;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #7938a4;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
}
</style>