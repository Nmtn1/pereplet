<?php
$this->title = 'Редактирование: ' . $model->getFullName();
?>

<div class="card">
    <div class="card-header">
        <h2>Редактирование автора</h2>
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" style="background: #6b7280;" class="btn-primary">← Назад</a>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>