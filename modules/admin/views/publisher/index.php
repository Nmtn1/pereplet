<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Издательства';
?>

<div class="card">
    <div class="card-header">
        <h2>Управление издательствами</h2>
        <a href="<?= Url::to(['create']) ?>" class="btn-primary">+ Добавить издательство</a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Сайт</th>
                    <th>Книг</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->models as $publisher): ?>
                <tr>
                    <td><?= $publisher->id ?></td>
                    <td><strong><?= Html::encode($publisher->name) ?></strong></td>
                    <td><?= Html::encode($publisher->phone ?: '—') ?></td>
                    <td><?= $publisher->email ? Html::encode($publisher->email) : '—' ?></td>
                    <td><?= $publisher->website ? Html::encode($publisher->website) : '—' ?></td>
                    <td><?= $publisher->getBooks()->count() ?></td>
                    <td>
                        <a href="<?= Url::to(['view', 'id' => $publisher->id]) ?>" class="btn-sm btn-outline">Просмотр</a>
                        <a href="<?= Url::to(['update', 'id' => $publisher->id]) ?>" class="btn-sm btn-outline">Редакт.</a>
                        <a href="<?= Url::to(['delete', 'id' => $publisher->id]) ?>" class="btn-sm btn-outline" style="color: #dc2626;" data-confirm="Удалить издательство?" data-method="post">Удалить</a>
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