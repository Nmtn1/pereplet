<?php
$this->title = 'Добавление издательства';
?>

<div class="card">
    <div class="card-header">
        <h2>Добавление издательства</h2>
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" style="background: #6b7280;" class="btn-primary">← Назад</a>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>