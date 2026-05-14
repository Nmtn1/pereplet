<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои отзывы';
?>

<div class="breadcrumbs">
    <div class="container">
        <a href="<?= Url::home() ?>">Главная</a> /
        <a href="<?= Url::to(['/profile/index']) ?>">Личный кабинет</a> /
        <span>Мои отзывы</span>
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
                    <a href="<?= Url::to(['/profile/orders']) ?>" class="profile-nav__link">Мои заказы</a>
                    <a href="<?= Url::to(['/profile/bookmarks']) ?>" class="profile-nav__link">Избранное</a>
                    <a href="<?= Url::to(['/profile/reviews']) ?>" class="profile-nav__link active">Мои отзывы</a>
                    <a href="<?= Url::to(['/profile/bonus']) ?>" class="profile-nav__link">Бонусная программа</a>
                </nav>
                <a href="<?= Url::to(['/site/logout']) ?>" class="btn btn--outline logout-btn" data-method="post">Выйти</a>
            </aside>

            <div class="profile-content">
                <div class="profile-card glass">
                    <div class="profile-card__title">
                        Мои отзывы
                        <span style="float: right; font-size: 14px; font-weight: normal;">
                            <?= count($reviews) ?> отзывов
                        </span>
                    </div>

                    <?php if (empty($reviews)): ?>
                        <div class="empty-state" style="text-align: center; padding: 40px;">
                            <p style="font-size: 48px; margin-bottom: 16px;">💬</p>
                            <p style="color: #64748b;">У вас пока нет отзывов</p>
                            <p style="color: #9ca3af; font-size: 14px; margin-top: 8px;">После получения заказа вы сможете оставить отзыв на книги</p>
                            <a href="<?= Url::to(['/profile/orders']) ?>" class="btn btn--primary" style="margin-top: 20px;">Мои заказы</a>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <?php foreach ($reviews as $review): ?>
                                <div style="border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                                        <div>
                                            <a href="<?= Url::to(['/catalog/view', 'slug' => $review->book->slug]) ?>" style="font-size: 18px; font-weight: 600; color: #1a1a2e; text-decoration: none;">
                                                <?= Html::encode($review->book->title) ?>
                                            </a>
                                            <div style="margin-top: 8px;">
                                                <span style="color: #ffc107; font-size: 18px;">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?= $i <= $review->rating ? '★' : '☆' ?>
                                                    <?php endfor; ?>
                                                </span>
                                                <span style="margin-left: 8px; color: #64748b;">(<?= $review->rating ?>/5)</span>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 12px; color: #64748b;">
                                                <?= Yii::$app->formatter->asDate($review->created_at, 'dd.MM.yyyy') ?>
                                            </div>
                                            <?php if ($review->is_approved): ?>
                                                <span class="badge badge-green" style="font-size: 11px;">Опубликован</span>
                                            <?php else: ?>
                                                <span class="badge" style="background: #ffedd5; color: #9a3412; font-size: 11px;">На модерации</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($review->title): ?>
                                        <div style="font-weight: 600; margin-bottom: 8px;">
                                            <?= Html::encode($review->title) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div style="color: #4b5563; margin-bottom: 16px;">
                                        <?= nl2br(Html::encode($review->comment)) ?>
                                    </div>
                                    
                                    <?php if ($review->pros || $review->cons): ?>
                                        <div style="display: flex; gap: 20px; margin-bottom: 16px; font-size: 14px;">
                                            <?php if ($review->pros): ?>
                                                <div><span style="color: #27ae60;">Достоинства:</span> <?= Html::encode($review->pros) ?></div>
                                            <?php endif; ?>
                                            <?php if ($review->cons): ?>
                                                <div><span style="color: #dc2626;">Недостатки:</span> <?= Html::encode($review->cons) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div style="display: flex; gap: 12px;">
                                        <a href="<?= Url::to(['/catalog/view', 'slug' => $review->book->slug]) ?>" class="btn-sm btn-outline">Перейти к книге</a>
                                        <?php if (!$review->is_approved): ?>
                                            <a href="<?= Url::to(['delete-review', 'id' => $review->id]) ?>" class="btn-sm btn-outline" style="color: #dc2626;" data-method="post" data-confirm="Удалить отзыв?">Удалить</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>