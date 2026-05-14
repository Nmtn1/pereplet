<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Книги';
?>

<div class="card">
    <div class="card-header">
        <h2>Управление книгами</h2>
        <a href="<?= Url::to(['create']) ?>" class="btn-primary">+ Добавить книгу</a>
    </div>

    <?php if ($dataProvider->totalCount > 0): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Цена</th>
                        <th>Остаток</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider->models as $book): ?>
                    <tr>
                        <td><?= $book->id ?></td>
                        <td><strong><?= Html::encode($book->title) ?></strong></td>
                        <td><?= $book->author ? Html::encode($book->author->getFullName()) : '-' ?></td>
                        <td><?= number_format($book->price, 0, '', ' ') ?> ₽</td>
                        <td>
                            <?php if ($book->stock > 0): ?>
                                <?= $book->stock ?> шт.
                            <?php else: ?>
                                <span style="color: #dc2626;">Нет</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($book->is_bestseller): ?>
                                <span class="badge badge-green">Хит</span>
                            <?php endif; ?>
                            <?php if ($book->is_new): ?>
                                <span class="badge badge-orange">Новинка</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= Url::to(['view', 'id' => $book->id]) ?>" class="btn-sm btn-outline">Просмотр</a>
                            <a href="<?= Url::to(['update', 'id' => $book->id]) ?>" class="btn-sm btn-outline">Редакт.</a>
                            <a href="<?= Url::to(['delete', 'id' => $book->id]) ?>" class="btn-sm btn-outline" style="color: #dc2626;" data-confirm="Удалить книгу?" data-method="post">Удалить</a>
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
        
    <?php else: ?>
        <div class="empty-state">
            <p>📭 В каталоге пока нет книг</p>
            <p>Нажмите «+ Добавить книгу», чтобы создать первую</p>
        </div>
    <?php endif; ?>
</div>