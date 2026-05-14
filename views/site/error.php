<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name ?? 'Ошибка';
?>

<div class="error-page" style="min-height: 60vh; display: flex; align-items: center; padding: 80px 0;">
    <div class="container">
        <div class="error-content glass" style="text-align: center; padding: 60px; max-width: 600px; margin: 0 auto;">
            <div class="error-code" style="font-size: 120px; font-weight: 800; color: var(--primary); line-height: 1;">
                <?= $exception->statusCode ?? '404' ?>
            </div>
            <div class="error-icon" style="font-size: 64px; margin: 20px 0;"></div>
            <h1 class="error-title" style="font-size: 28px; margin-bottom: 16px;"><?= Html::encode($name) ?></h1>
            <p class="error-text" style="color: var(--gray); margin-bottom: 32px;">
                <?= nl2br(Html::encode($message)) ?>
            </p>
            <div class="error-actions" style="display: flex; gap: 16px; justify-content: center;">
                <a href="<?= Url::home() ?>" class="btn btn--primary">На главную</a>
                <a href="<?= Url::to(['/catalog/index']) ?>" class="btn btn--outline">В каталог</a>
            </div>
        </div>
    </div>
</div>