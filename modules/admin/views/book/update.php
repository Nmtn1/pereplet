<?php
use yii\helpers\Html;
$this->title = 'Редактирование книги';
?>

<div class="card">
    <div class="card-header">
        <h2>Редактирование книги</h2>
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">← Назад</a>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
        'authors' => $authors,
        'publishers' => $publishers,
        'categories' => $categories,
    ]) ?>
</div>