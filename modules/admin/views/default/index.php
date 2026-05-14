<?php
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Панель управления';
?>

<div class="card">
    <div class="card-header">
        <h2>Панель управления</h2>
        <span style="font-size: 13px; color: #64748b;"><?= date('d.m.Y') ?></span>
    </div>
    <p style="color: #475569; margin-top: 4px;">Добро пожаловать в админ-панель книжного магазина Переплёт</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Новые заказы</h3>
        <div class="stat-number"><?= $newOrders ?? 0 ?></div>
        <a href="<?= Url::to(['/admin/order/index', 'OrderSearch' => ['status' => 'new']]) ?>" class="stat-link">Посмотреть →</a>
    </div>
    <div class="stat-card">
        <h3>Пользователи</h3>
        <div class="stat-number"><?= $totalUsers ?? 0 ?></div>
        <a href="<?= Url::to(['/admin/user/index']) ?>" class="stat-link">Управление →</a>
    </div>
    <div class="stat-card">
        <h3>Книги</h3>
        <div class="stat-number"><?= $totalBooks ?? 0 ?></div>
        <a href="<?= Url::to(['/admin/book/index']) ?>" class="stat-link">Каталог →</a>
    </div>
    <div class="stat-card">
        <h3>Выручка</h3>
        <div class="stat-number"><?= number_format($totalRevenue ?? 0, 0, '', ' ') ?> руб.</div>
        <a href="<?= Url::to(['/admin/order/index']) ?>" class="stat-link">Все заказы →</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Последние заказы</h2>
        <a href="<?= Url::to(['/admin/order/index']) ?>" class="stat-link" style="font-size: 13px;">Все заказы →</a>
    </div>

    <?php
    $recentOrders = \app\models\Order::find()
        ->orderBy(['created_at' => SORT_DESC])
        ->limit(8)
        ->all();
    ?>

    <?php if (!empty($recentOrders)): ?>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>№ заказа</th>
                        <th>Покупатель</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><strong>#<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></strong></td>
                            <td>
                                <?php if ($order->user): ?>
                                    <?= Html::encode($order->user->email) ?>
                                <?php else: ?>
                                    <span style="color: #9ca3af;">Гость (<?= Html::encode($order->guest_email) ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= number_format($order->total_amount, 0, '', ' ') ?> руб.</strong></td>
                            <td>
                                <?php
                                $statusLabels = [
                                    'new' => 'Новый',
                                    'processing' => 'В обработке',
                                    'shipped' => 'Отправлен',
                                    'delivered' => 'Доставлен',
                                    'cancelled' => 'Отменён',
                                ];
                                $statusColors = [
                                    'new' => '#fef3c7',
                                    'processing' => '#fed7aa',
                                    'shipped' => '#dbeafe',
                                    'delivered' => '#dcfce7',
                                    'cancelled' => '#f3f4f6',
                                ];
                                $color = $statusColors[$order->status] ?? '#f3f4f6';
                                ?>
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; background: <?= $color ?>;">
                                    <?= $statusLabels[$order->status] ?? $order->status ?>
                                </span>
                            </td>
                            <td><?= Yii::$app->formatter->asDate($order->created_at, 'dd.MM.yyyy') ?></td>
                            <td><a href="<?= Url::to(['/admin/order/view', 'id' => $order->id]) ?>" class="btn-sm btn-outline">Детали</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>Пока нет заказов</p>
            <p>После оформления появятся здесь</p>
        </div>
    <?php endif; ?>
</div>

<div class="stats-grid" style="margin-top: 0;">
    <div class="stat-card">
        <h3>Авторы</h3>
        <div class="stat-number"><?= \app\models\Author::find()->count() ?></div>
        <a href="<?= Url::to(['/admin/author/index']) ?>" class="stat-link">Управление →</a>
    </div>
    <div class="stat-card">
        <h3>Издательства</h3>
        <div class="stat-number"><?= \app\models\Publisher::find()->count() ?></div>
        <a href="<?= Url::to(['/admin/publisher/index']) ?>" class="stat-link">Управление →</a>
    </div>
    <div class="stat-card">
        <h3>Отзывы</h3>
        <div class="stat-number"><?= \app\models\Reviews::find()->where(['is_approved' => 0])->count() ?></div>
        <a href="<?= Url::to(['/admin/reviews/index', 'ReviewsSearch' => ['is_approved' => 0]]) ?>" class="stat-link">На модерации →</a>
    </div>
    <div class="stat-card">
        <h3>Категории</h3>
        <div class="stat-number"><?= \app\models\Category::find()->count() ?></div>
        <a href="<?= Url::to(['/admin/category/index']) ?>" class="stat-link">Управление →</a>
    </div>
</div>