<?php
use yii\helpers\Url;
use yii\helpers\Html;

$x = 0;

$this->title = $model->title;
?>

<div class="card">
    <div class="card-header">
        <h2>Просмотр книги</h2>
        <div style="display: flex; gap: 12px;">
            <a href="<?= Url::to(['update', 'id' => $model->id]) ?>" class="btn-admin">Редактировать</a>
            <a href="<?= Url::to(['index']) ?>" class="btn-admin-outline">Назад к списку</a>
        </div>
    </div>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        <div style="width: 280px; flex-shrink: 0;">
            <div id="mainImageContainer" style="background: #f8fafc; border-radius: 16px; padding: 15px; text-align: center;">
                <?php $mainImage = $model->getMainImage()->one(); ?>
                <?php if ($mainImage): ?>
                    <img id="mainImage" src="<?= $mainImage->image_path ?>" alt="<?= Html::encode($model->title) ?>" style="width: 100%; max-width: 100%; height: auto; aspect-ratio: 2/3; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); cursor: pointer;" onclick="openImageModal(this.src)">
                <?php else: ?>
                    <div id="mainImage" style="width: 100%; aspect-ratio: 2/3; background: #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">Нет изображения</div>
                <?php endif; ?>
            </div>
            <?php $images = $model->getImages()->all(); ?>
            <?php if (count($images) > 0): ?>
                <div style="margin-top: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <div style="font-size: 13px; color: #64748b;">Все изображения (<?= count($images) ?>/20):</div>
                        <?php if (count($images) >= 20): ?>
                            <div style="font-size: 11px; color: #dc2626;">Достигнут лимит (20)</div>
                        <?php endif; ?>
                    </div>
                    <div class="thumb-scroll" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap; padding-bottom: 10px; max-width: 100%;">
                        <div style="display: inline-flex; gap: 12px; padding: 5px 2px;">
                            <?php foreach ($images as $img): ?>
                                <div class="thumb-item" style="display: inline-flex; flex-direction: column; align-items: center; cursor: pointer; border: 3px solid <?= $img->is_main ? '#7938a4' : '#e5e7eb' ?>; border-radius: 10px; padding: 5px; background: white; transition: all 0.2s; width: 90px;">
                                    <img src="<?= $img->image_path ?>" style="width: 80px; height: 110px; object-fit: cover; border-radius: 6px;" onclick="openImageModal('<?= $img->image_path ?>')">
                                    <div style="display: flex; gap: 5px; margin-top: 5px; flex-wrap: wrap; justify-content: center;">
                                        <?php if (!$img->is_main): ?>
                                            <button class="btn-sm btn-outline" style="font-size: 9px; padding: 2px 6px;" onclick="event.stopPropagation(); setMainImage(<?= $img->id ?>, '<?= $img->image_path ?>', this.parentElement.parentElement)">Главной</button>
                                        <?php else: ?>
                                            <span style="font-size: 9px; color: #7938a4; padding: 2px 6px;">Главная</span>
                                        <?php endif; ?>
                                        <button class="btn-sm btn-outline" style="font-size: 9px; padding: 2px 6px; color: #dc2626; border-color: #fecaca;" onclick="event.stopPropagation(); deleteImage(<?= $img->id ?>, this)">Удалить</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <small style="color: #64748b; display: block; margin-top: 8px;">Листайте вправо | Кликните на изображение для увеличения | Максимум 20</small>
                </div>
            <?php else: ?>
                <div style="margin-top: 16px; padding: 12px; background: #fef3c7; border-radius: 8px; font-size: 13px; color: #92400e;">
                    Изображения отсутствуют. Загрузите их при редактировании.
                </div>
            <?php endif; ?>
        </div>

        <div style="flex: 1; min-width: 0;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div><strong style="color: #64748b;">ID:</strong><br><?= $model->id ?></div>
                <div><strong style="color: #64748b;">Артикул (ISBN):</strong><br><?= Html::encode($model->isbn ?: '—') ?></div>
                
                <div><strong style="color: #64748b;">Название:</strong><br><?= Html::encode($model->title) ?></div>
                <div><strong style="color: #64748b;">Автор:</strong><br><?= $model->author ? Html::encode($model->author->getFullName()) : '—' ?></div>
                
                <div><strong style="color: #64748b;">Категория:</strong><br><?= $model->mainCategory ? Html::encode($model->mainCategory->name) : '—' ?></div>
                <div><strong style="color: #64748b;">Издательство:</strong><br><?= $model->publisher ? Html::encode($model->publisher->name) : '—' ?></div>
                
                <div><strong style="color: #64748b;">Цена:</strong><br><span style="font-size: 24px; font-weight: 700; color: #7938a4;"><?= number_format($model->price, 0, '', ' ') ?> руб.</span></div>
                <div>
                    <strong style="color: #64748b;">Старая цена:</strong><br>
                    <?php if ($model->old_price): ?>
                        <span style="text-decoration: line-through; color: #94a3b8;"><?= number_format($model->old_price, 0, '', ' ') ?> руб.</span>
                        <span style="color: #c2410c;"> (-<?= $model->getDiscountPercent() ?>%)</span>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </div>
                
                <div><strong style="color: #64748b;">Остаток:</strong><br><?= $model->stock ?> шт.</div>
                <div><strong style="color: #64748b;">Продано:</strong><br><?= $model->sales_count ?> шт.</div>
                
                <div><strong style="color: #64748b;">Страниц:</strong><br><?= $model->pages ?: '—' ?></div>
                <div><strong style="color: #64748b;">Год издания:</strong><br><?= $model->year ?: '—' ?></div>
                
                <div><strong style="color: #64748b;">Переплёт:</strong><br><?= Html::encode($model->cover_type ?: '—') ?></div>
                <div><strong style="color: #64748b;">Формат:</strong><br><?= Html::encode($model->format ?: '—') ?></div>
                
                <div><strong style="color: #64748b;">Вес:</strong><br><?= $model->weight ? $model->weight . ' г' : '—' ?></div>
                <div><strong style="color: #64748b;">Рейтинг:</strong><br><?= $model->getRatingAverage() ?> (<?= $model->rating_count ?> отзывов)</div>
                
                <div><strong style="color: #64748b;">Хит продаж:</strong><br><?= $model->is_bestseller ? 'Да' : 'Нет' ?></div>
                <div><strong style="color: #64748b;">Новинка:</strong><br><?= $model->is_new ? 'Да' : 'Нет' ?></div>
                
                <div><strong style="color: #64748b;">Просмотров:</strong><br><?= $model->views_count ?></div>
                <div><strong style="color: #64748b;">Создано:</strong><br><?= Yii::$app->formatter->asDate($model->created_at, 'dd.MM.yyyy H:i') ?></div>
            </div>

            <div style="margin-top: 24px;">
                <strong style="color: #64748b;">Описание:</strong>
                <div style="background: #f8fafc; padding: 16px; border-radius: 16px; margin-top: 8px; max-height: 300px; overflow-y: auto;">
                    <?= nl2br(Html::encode($model->description ?: '—')) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer; align-items: center; justify-content: center;">
    <img id="modalImage" src="" style="max-width: 90%; max-height: 90%; object-fit: contain;">
    <span style="position: absolute; top: 20px; right: 40px; color: white; font-size: 40px; cursor: pointer;" onclick="closeImageModal()">&times;</span>
</div>

<script>
function setMainImage(imageId, imagePath, element) {
    console.log('set main image', imageId);
    document.getElementById('mainImage').src = imagePath;
    
    fetch('<?= Url::to(['set-main-image', 'id' => '__ID__']) ?>'.replace('__ID__', imageId), {
        method: 'POST',
        headers: {
            'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>',
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.thumb-item').forEach(thumb => {
                thumb.style.border = '3px solid #e5e7eb';
            });
            element.style.border = '3px solid #7938a4';
            showNotification('Главное изображение обновлено');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Ошибка', 'error');
        }
    });
}

function deleteImage(imageId, button) {
    if (confirm('Удалить изображение?')) {
        fetch('<?= Url::to(['delete-image', 'id' => '__ID__']) ?>'.replace('__ID__', imageId), {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>',
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Изображение удалено');
                location.reload();
            } else {
                showNotification('Ошибка', 'error');
            }
        });
    }
}

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

function showNotification(message, type = 'success') {
    let div = document.createElement('div');
    div.textContent = message;
    div.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#dcfce7' : '#fee2e2'};
        color: ${type === 'success' ? '#166534' : '#991b1b'};
        border-radius: 12px;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    `;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}
</script>

<style>
.thumb-item {
    transition: all 0.2s ease;
    width: 90px !important;
    flex-shrink: 0 !important;
}
.thumb-item:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.thumb-scroll {
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    padding-bottom: 10px;
    max-width: 100%;
}
.thumb-scroll::-webkit-scrollbar {
    height: 6px;
}
.thumb-scroll::-webkit-scrollbar-track {
    background: #e2e8f0;
    border-radius: 10px;
}
.thumb-scroll::-webkit-scrollbar-thumb {
    background: #7938a4;
    border-radius: 10px;
}
</style>