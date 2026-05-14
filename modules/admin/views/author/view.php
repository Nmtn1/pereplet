<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = $model->getFullName();
?>

<div class="card">
    <div class="card-header">
        <h2><?= Html::encode($model->getFullName()) ?></h2>
        <div style="display: flex; gap: 12px;">
            <a href="<?= Url::to(['update', 'id' => $model->id]) ?>" class="btn-primary">✎ Редактировать</a>
            <a href="<?= Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">← Назад</a>
        </div>
    </div>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        <?php if ($model->photo): ?>
        <div style="width: 200px;">
            <img src="<?= $model->photo ?>" style="width: 100%; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        </div>
        <?php endif; ?>
        
        <div style="flex: 1;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div><strong>ID:</strong><br><?= $model->id ?></div>
                <div><strong>Slug:</strong><br><?= Html::encode($model->slug) ?></div>
                <div><strong>Дата рождения:</strong><br><?= $model->birth_date ?: '—' ?></div>
                <div><strong>Дата смерти:</strong><br><?= $model->death_date ?: '—' ?></div>
            </div>
            
            <?php if ($model->biography): ?>
            <div style="margin-top: 24px;">
                <strong>Биография:</strong>
                <div style="background: #f8fafc; padding: 16px; border-radius: 16px; margin-top: 8px;">
                    <?= nl2br(Html::encode($model->biography)) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div style="margin-top: 24px;">
                <strong>Книги автора (<?= $model->getBooks()->count() ?>):</strong>
                <div style="margin-top: 12px;">
                    <?php foreach ($model->getBooks()->limit(10)->all() as $book): ?>
                        <a href="<?= Url::to(['/admin/book/view', 'id' => $book->id]) ?>" style="display: inline-block; background: #f0e6f5; padding: 6px 12px; border-radius: 20px; margin: 4px; text-decoration: none; color: #7938a4;">
                            <?= Html::encode($book->title) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>