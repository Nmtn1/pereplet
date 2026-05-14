<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Распродажа';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> / 
        <span>Распродажа</span>
    </div>
</div>

<div class="sales-page">
    <div class="container">
        <div class="sales-header">
            <h1 class="sales-title">Горячая распродажа</h1>
            <div class="countdown">
                <span class="countdown__label">До конца распродажи:</span>
                <div class="countdown__timer" id="countdown">
                    <span class="timer-block">12<span>дней</span></span>
                    <span class="timer-block">08<span>часов</span></span>
                    <span class="timer-block">45<span>минут</span></span>
                    <span class="timer-block">30<span>секунд</span></span>
                </div>
            </div>
        </div>

        <!-- Баннер скидок -->
        <div class="sale-banner glass">
            <div class="sale-banner__content">
                <span class="sale-banner__discount">СКИДКИ ДО 70%</span>
                <h2>Весенний книжный марафон</h2>
                <p>Тысячи книг по суперценам. Успейте, пока предложение действует!</p>
            </div>
        </div>

        <!-- Фильтры по скидкам -->
        <div class="sales-filters">
            <button class="filter-btn active" data-filter="all">Все скидки</button>
            <button class="filter-btn" data-filter="20">-20% и более</button>
            <button class="filter-btn" data-filter="30">-30% и более</button>
            <button class="filter-btn" data-filter="50">-50% и более</button>
        </div>

        <!-- Товары со скидкой -->
        <div class="books-grid sales-grid" id="salesGrid">
            <?php if (empty($saleBooks)): ?>
                <div class="empty-state glass">
                    <p>😕 Товары со скидкой временно отсутствуют</p>
                    <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>
            <?php else: ?>
                <?php foreach ($saleBooks as $book): ?>
                    <?php 
                    $discountPercent = $book->getDiscountPercent();
                    $finalPrice = $book->getFinalPrice();
                    ?>
                    <div class="book-card glass" data-discount="<?= $discountPercent ?>">
                        <div class="book-card__badge badge-sale">-<?= $discountPercent ?>%</div>
                        <div class="book-card__image">
                            <img src="<?= $book->getImageUrl() ?>" alt="<?= Html::encode($book->title) ?>">
                            <div class="book-card__overlay">
                                <a href="<?= Url::to(['/catalog/view', 'slug' => $book->slug]) ?>" class="btn-quick">Быстрый просмотр</a>
                            </div>
                        </div>
                        <div class="book-card__content">
                            <div class="book-card__price">
                                <span class="old-price"><?= number_format($book->price, 0, '', ' ') ?> ₽</span>
                                <?= number_format($finalPrice, 0, '', ' ') ?> ₽
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

<script>
// Фильтрация по скидкам
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');
        
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const cards = document.querySelectorAll('#salesGrid .book-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const discount = parseInt(card.getAttribute('data-discount'));
            
            if (filter === 'all') {
                card.style.display = 'block';
                visibleCount++;
            } else if (filter === '20' && discount >= 20) {
                card.style.display = 'block';
                visibleCount++;
            } else if (filter === '30' && discount >= 30) {
                card.style.display = 'block';
                visibleCount++;
            } else if (filter === '50' && discount >= 50) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Если ничего не найдено, показываем сообщение
        let emptyMsg = document.getElementById('no-results-msg');
        if (visibleCount === 0) {
            if (!emptyMsg) {
                const grid = document.getElementById('salesGrid');
                const msg = document.createElement('div');
                msg.id = 'no-results-msg';
                msg.className = 'empty-state glass';
                msg.innerHTML = '<p>😕 Книги с такими скидками не найдены</p>';
                grid.appendChild(msg);
            }
        } else if (emptyMsg) {
            emptyMsg.remove();
        }
    });
});

// Таймер обратного отсчёта (до 31 мая 2026)
function updateCountdown() {
    const targetDate = new Date(2026, 4, 31, 23, 59, 59); // 31 мая 2026
    const now = new Date();
    const diff = targetDate - now;
    
    if (diff <= 0) {
        const timer = document.getElementById('countdown');
        if (timer) timer.innerHTML = '<span class="timer-block">Акция завершена</span>';
        return;
    }
    
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (86400000)) / (3600000));
    const minutes = Math.floor((diff % 3600000) / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);
    
    const timerBlocks = document.querySelectorAll('#countdown .timer-block');
    if (timerBlocks.length >= 4) {
        timerBlocks[0].innerHTML = days + '<span>дней</span>';
        timerBlocks[1].innerHTML = String(hours).padStart(2, '0') + '<span>часов</span>';
        timerBlocks[2].innerHTML = String(minutes).padStart(2, '0') + '<span>минут</span>';
        timerBlocks[3].innerHTML = String(seconds).padStart(2, '0') + '<span>секунд</span>';
    }
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>

<style>
/* Дополнительные стили для страницы распродажи */
.sales-filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 32px;
    justify-content: center;
}

.filter-btn {
    padding: 10px 24px;
    border: none;
    background: #f1f5f9;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn.active,
.filter-btn:hover {
    background: #7938a4;
    color: white;
}

.sales-grid {
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

/* Адаптив для сетки */
@media (max-width: 1200px) {
    .sales-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 992px) {
    .sales-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .sales-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .filter-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .sales-grid {
        grid-template-columns: 1fr;
    }
}
</style>