<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = $model->name;
?>

<div class="card">
    <div class="card-header">
        <h2><?= Html::encode($model->name) ?></h2>
        <div style="display: flex; gap: 12px;">
            <a href="<?= Url::to(['update', 'id' => $model->id]) ?>" class="btn-primary">Редактировать</a>
            <a href="<?= Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">← Назад</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div><strong>ID:</strong><br><?= $model->id ?></div>
            <div style="margin-top: 16px;"><strong>Название:</strong><br><?= Html::encode($model->name) ?></div>
            <?php if ($model->phone): ?>
            <div style="margin-top: 16px;"><strong>Телефон:</strong><br><?= Html::encode($model->phone) ?></div>
            <?php endif; ?>
        </div>
        <div>
            <?php if ($model->email): ?>
            <div><strong>Email:</strong><br><a href="mailto:<?= $model->email ?>"><?= Html::encode($model->email) ?></a></div>
            <?php endif; ?>
            <?php if ($model->website): ?>
            <div style="margin-top: 16px;"><strong>Сайт:</strong><br><a href="<?= $model->website ?>" target="_blank"><?= Html::encode($model->website) ?></a></div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($model->address): ?>
    <div style="margin-top: 24px;">
        <strong>Адрес:</strong>
        <div style="background: #f8fafc; padding: 16px; border-radius: 16px; margin-top: 8px;">
            <?= nl2br(Html::encode($model->address)) ?>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 24px;">
        <strong>Книги издательства (<?= $model->getBooks()->count() ?>):</strong>
        <div style="margin-top: 12px;">
            <?php foreach ($model->getBooks()->limit(10)->all() as $book): ?>
                <a href="<?= Url::to(['/admin/book/view', 'id' => $book->id]) ?>" style="display: inline-block; background: #f0e6f5; padding: 6px 12px; border-radius: 20px; margin: 4px; text-decoration: none; color: #7938a4;">
                    <?= Html::encode($book->title) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>