<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Отзыв #' . $model->id;
?>

<div class="card">
    <div class="card-header">
        <h2>Отзыв на книгу</h2>
        <div style="display: flex; gap: 12px;">
            <?php if (!$model->is_approved): ?>
                <a href="<?= Url::to(['approve', 'id' => $model->id]) ?>" class="btn-primary" style="background: #27ae60;" data-method="post">Одобрить</a>
            <?php else: ?>
                <a href="<?= Url::to(['unapprove', 'id' => $model->id]) ?>" class="btn-primary" style="background: #f17000;" data-method="post">Скрыть</a>
            <?php endif; ?>
            <a href="<?= Url::to(['delete', 'id' => $model->id]) ?>" class="btn-primary" style="background: #dc2626;" data-method="post" data-confirm="Удалить отзыв?">Удалить</a>
            <a href="<?= Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">Назад</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div><strong>ID:</strong><br><?= $model->id ?></div>
            <div style="margin-top: 16px;"><strong>Книга:</strong><br>
                <a href="<?= Url::to(['/admin/book/view', 'id' => $model->book_id]) ?>" style="color: #7938a4;">
                    <?= Html::encode($model->book->title ?? '—') ?>
                </a>
            </div>
            <div style="margin-top: 16px;"><strong>Пользователь:</strong><br>
                <?= $model->user ? Html::encode($model->user->email) : 'Гость' ?>
                <?php if ($model->user): ?>
                    <br><small>ID: <?= $model->user_id ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <div><strong>Рейтинг:</strong><br>
                <span style="color: #ffc107; font-size: 20px;"><?= $model->getStarsHtml() ?></span>
                <span style="font-size: 18px; font-weight: 700;"><?= $model->rating ?></span>/5
            </div>
            <div style="margin-top: 16px;"><strong>Дата создания:</strong><br><?= Yii::$app->formatter->asDatetime($model->created_at) ?></div>
            <?php if ($model->updated_at != $model->created_at): ?>
            <div style="margin-top: 16px;"><strong>Обновлён:</strong><br><?= Yii::$app->formatter->asDatetime($model->updated_at) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($model->title): ?>
    <div style="margin-top: 24px;">
        <strong>Заголовок отзыва:</strong>
        <div style="background: #f8fafc; padding: 12px 16px; border-radius: 12px; margin-top: 8px; font-size: 18px; font-weight: 500;">
            <?= Html::encode($model->title) ?>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 24px;">
        <strong>Текст отзыва:</strong>
        <div style="background: #f8fafc; padding: 16px; border-radius: 16px; margin-top: 8px; line-height: 1.6;">
            <?= nl2br(Html::encode($model->comment)) ?>
        </div>
    </div>

    <?php if ($model->pros): ?>
    <div style="margin-top: 24px;">
        <strong>Достоинства:</strong>
        <div style="background: #e8f5e9; padding: 12px 16px; border-radius: 12px; margin-top: 8px;">
            <?= nl2br(Html::encode($model->pros)) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($model->cons): ?>
    <div style="margin-top: 24px;">
        <strong>Недостатки:</strong>
        <div style="background: #ffebee; padding: 12px 16px; border-radius: 12px; margin-top: 8px;">
            <?= nl2br(Html::encode($model->cons)) ?>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 24px; padding: 16px; background: #f1f5f9; border-radius: 16px; display: flex; gap: 20px;">
        <div>Полезных: <?= $model->helpful_count ?></div>
        <div>Неполезных: <?= $model->not_helpful_count ?></div>
        <div>Подтверждён: <?= $model->is_verified ? 'Да' : 'Нет' ?></div>
        <div>Статус: <?= $model->is_approved ? '<span style="color: #27ae60;">Опубликован</span>' : '<span style="color: #f17000;">На модерации</span>' ?></div>
    </div>
</div>