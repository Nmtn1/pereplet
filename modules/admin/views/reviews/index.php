<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$x = 0;

$this->title = 'Отзывы';
?>

<div class="card">
    <div class="card-header">
        <h2>Управление отзывами</h2>
        <div>
            <a href="<?= Url::to(['index', 'ReviewsSearch' => ['is_approved' => 0]]) ?>" class="btn-primary" style="background: #f17000;">На модерации</a>
            <a href="<?= Url::to(['index', 'ReviewsSearch' => ['is_approved' => 1]]) ?>" class="btn-primary" style="background: #27ae60;">Опубликованные</a>
            <a href="<?= Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">Все</a>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => ['class' => 'search-form', 'style' => 'padding: 16px; background: #f8fafc; border-radius: 16px; margin-bottom: 20px;']
    ]); ?>
    <div class="row" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <?= Html::activeTextInput($searchModel, 'book_title', ['class' => 'form-control', 'style' => 'flex:1;', 'placeholder' => 'Название книги']) ?>
        <?= Html::activeTextInput($searchModel, 'user_name', ['class' => 'form-control', 'style' => 'flex:1;', 'placeholder' => 'Пользователь']) ?>
        <?= Html::activeDropDownList($searchModel, 'rating', ['' => 'Любой рейтинг', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'], ['class' => 'form-control', 'style' => 'width: 150px;']) ?>
        <?= Html::activeDropDownList($searchModel, 'is_approved', ['' => 'Любой статус', 0 => 'На модерации', 1 => 'Опубликован'], ['class' => 'form-control', 'style' => 'width: 160px;']) ?>
        <?= Html::submitButton('Найти', ['class' => 'btn-primary', 'style' => 'background: #7938a4;']) ?>
        <a href="<?= Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">Сброс</a>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Книга</th>
                    <th>Пользователь</th>
                    <th>Рейтинг</th>
                    <th>Отзыв</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->models as $review): ?>
                <tr>
                    <td><input type="checkbox" class="review-checkbox" value="<?= $review->id ?>"></td>
                    <td><?= $review->id ?></td>
                    <td>>
                        <a href="<?= Url::to(['/admin/book/view', 'id' => $review->book_id]) ?>" style="color: #7938a4;">
                            <?= Html::encode($review->book->title ?? '—') ?>
                        </a>
                    </td>
                    <td><?= $review->user ? Html::encode($review->user->email) : '—' ?></td>
                    <td>>
                        <span style="color: #ffc107; font-size: 14px;"><?= $review->getStarsHtml() ?></span>
                        <small>(<?= $review->rating ?>)</small>
                    </div>
                    <td>
                        <strong><?= Html::encode($review->title) ?></strong><br>
                        <small style="color: #64748b;"><?= Html::encode(mb_substr($review->comment, 0, 80)) ?>...</small>
                    </div>
                    <td><?= Yii::$app->formatter->asDate($review->created_at, 'dd.MM.yyyy') ?></div>
                    <td>>
                        <?php if ($review->is_approved): ?>
                            <span class="badge badge-green">Опубликован</span>
                        <?php else: ?>
                            <span class="badge badge-orange">На модерации</span>
                        <?php endif; ?>
                    </div>
                    <td>>
                        <?php if (!$review->is_approved): ?>
                            <a href="<?= Url::to(['approve', 'id' => $review->id]) ?>" class="btn-sm btn-outline" style="background: #27ae60; color: white;" data-method="post">Одобрить</a>
                        <?php else: ?>
                            <a href="<?= Url::to(['unapprove', 'id' => $review->id]) ?>" class="btn-sm btn-outline" style="background: #f17000; color: white;" data-method="post">Скрыть</a>
                        <?php endif; ?>
                        <a href="<?= Url::to(['view', 'id' => $review->id]) ?>" class="btn-sm btn-outline">Просмотр</a>
                        <a href="<?= Url::to(['delete', 'id' => $review->id]) ?>" class="btn-sm btn-outline" style="color: #dc2626;" data-method="post" data-confirm="Удалить отзыв?">Удалить</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($dataProvider->count > 0): ?>
    <div style="margin-top: 16px; display: flex; gap: 12px; align-items: center;">
        <button id="mass-approve-btn" class="btn-primary" style="background: #27ae60;">Одобрить выбранные</button>
        <span id="selected-count" style="color: #64748b;">Выбрано: 0</span>
    </div>
    <?php endif; ?>

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

<script>
document.getElementById('select-all')?.addEventListener('change', function(e) {
    document.querySelectorAll('.review-checkbox').forEach(cb => cb.checked = e.target.checked);
    updateSelectedCount();
});

document.querySelectorAll('.review-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    let count = document.querySelectorAll('.review-checkbox:checked').length;
    document.getElementById('selected-count').innerText = 'Выбрано: ' + count;
}

document.getElementById('mass-approve-btn')?.addEventListener('click', function() {
    let ids = [];
    document.querySelectorAll('.review-checkbox:checked').forEach(cb => ids.push(cb.value));
    if (ids.length === 0) {
        alert('Выберите хотя бы один отзыв');
        return;
    }
    if (confirm('Одобрить ' + ids.length + ' отзыв(ов)?')) {
        let form = document.createElement('form');
        form.method = 'post';
        form.action = '<?= Url::to(['mass-approve']) ?>';
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids';
        input.value = JSON.stringify(ids);
        form.appendChild(input);
        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_csrf';
        csrf.value = '<?= Yii::$app->request->csrfToken ?>';
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }
});
</script>