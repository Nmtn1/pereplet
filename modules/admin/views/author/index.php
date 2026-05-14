<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Авторы';
?>

<div class="card">
    <div class="card-header">
        <h2>Управление авторами</h2>
        <a href="<?= Url::to(['create']) ?>" class="btn-primary">+ Добавить автора</a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Фото</th>
                    <th>ФИО</th>
                    <th>Книг</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->models as $author): ?>
                <tr>
                    <td><?= $author->id ?></td>
                    <td>
                        <?php if ($author->photo): ?>
                            <img src="<?= $author->photo ?>" style="width: 50px; height: 60px; object-fit: cover; border-radius: 8px;">
                        <?php else: ?>
                            <div style="width: 50px; height: 60px; background: #e2e8f0; border-radius: 8px;"></div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= Html::encode($author->getFullName()) ?></strong></td>
                    <td><?= $author->getBooks()->count() ?></td>
                    <td>
                        <a href="<?= Url::to(['view', 'id' => $author->id]) ?>" class="btn-sm btn-outline">Просмотр</a>
                        <a href="<?= Url::to(['update', 'id' => $author->id]) ?>" class="btn-sm btn-outline">Редакт.</a>
                        <a href="<?= Url::to(['delete', 'id' => $author->id]) ?>" class="btn-sm btn-outline" style="color: #dc2626;" data-confirm="Удалить автора?" data-method="post">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
    $pagination = $dataProvider->pagination;
    if ($pagination && $pagination->pageCount > 1):
    ?>
    <div class="pagination">
        <?php for ($i = 0; $i < $pagination->pageCount; $i++): ?>
            <?php $active = ($i == $pagination->page) ? 'active' : ''; ?>
            <a href="<?= Url::current(['page' => $i + 1]) ?>" class="page-link <?= $active ?>"><?= $i + 1 ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>