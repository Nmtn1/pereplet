<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row">
    <div>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите название книги']) ?>
        
        <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'kniga-nazvanie']) ?>
        
        <?= $form->field($model, 'isbn')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => '978-5-17-123456-7']) ?>
        
        <?= $form->field($model, 'author_id')->dropDownList(
            ArrayHelper::map($authors, 'id', 'full_name'),
            ['prompt' => '— Выберите автора —', 'class' => 'form-control']
        ) ?>
        
        <?= $form->field($model, 'publisher_id')->dropDownList(
            ArrayHelper::map($publishers, 'id', 'name'),
            ['prompt' => '— Выберите издательство —', 'class' => 'form-control']
        ) ?>
        
        <?= $form->field($model, 'category_id')->dropDownList(
            ArrayHelper::map($categories, 'id', 'name'),
            ['prompt' => '— Выберите категорию —', 'class' => 'form-control']
        ) ?>
    </div>
    
    <div>
        <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control', 'placeholder' => '1000']) ?>
        
        <?= $form->field($model, 'old_price')->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control', 'placeholder' => '1500']) ?>
        
        <?= $form->field($model, 'discount_percent')->textInput(['type' => 'number', 'class' => 'form-control', 'placeholder' => '20']) ?>
        
        <?= $form->field($model, 'stock')->textInput(['type' => 'number', 'class' => 'form-control', 'placeholder' => '100']) ?>
        
        <?= $form->field($model, 'pages')->textInput(['type' => 'number', 'class' => 'form-control', 'placeholder' => '256']) ?>
        
        <?= $form->field($model, 'year')->textInput(['type' => 'number', 'class' => 'form-control', 'placeholder' => '2024']) ?>
        
        <?= $form->field($model, 'cover_type')->dropDownList(
            ['твердый' => 'Твердый переплёт', 'мягкий' => 'Мягкий переплёт', 'суперобложка' => 'Суперобложка'],
            ['prompt' => '— Выберите тип —', 'class' => 'form-control']
        ) ?>
    </div>
</div>

<?= $form->field($model, 'description')->textarea(['rows' => 6, 'class' => 'form-control', 'placeholder' => 'Описание книги...']) ?>

<div class="row">
    <div>
        <?= $form->field($model, 'is_bestseller')->checkbox(['class' => 'checkbox-agree']) ?>
    </div>
    <div>
        <?= $form->field($model, 'is_new')->checkbox(['class' => 'checkbox-agree']) ?>
    </div>
</div>

<!-- ЗАГРУЗКА НОВЫХ ИЗОБРАЖЕНИЙ -->
<div class="form-group">
    <label>Загрузить новые изображения</label>
    <input type="file" name="images[]" multiple accept="image/*" class="form-control" style="padding: 8px;">
    <small style="color: #64748b;">
        Выберите одно или несколько изображений. Первое будет основным.
        <?php if (isset($images) && count($images) >= 20): ?>
            <span style="color: #dc2626;">Достигнут лимит (20 изображений)</span>
        <?php else: ?>
            Можно загрузить до 20 изображений (сейчас <?= isset($images) ? count($images) : 0 ?>/20)
        <?php endif; ?>
    </small>
</div>

<!-- ТЕКУЩИЕ ИЗОБРАЖЕНИЯ С ПРОКРУТКОЙ -->
<?php if (isset($images) && !empty($images)): ?>
<div class="form-group">
    <label>Текущие изображения</label>
    <div style="overflow-x: auto; white-space: nowrap; padding: 10px 0; border-radius: 12px; background: #f8fafc;">
        <div style="display: inline-flex; gap: 15px; padding: 0 5px;">
            <?php foreach ($images as $img): ?>
            <div style="display: inline-block; text-align: center; vertical-align: top; background: white; border-radius: 12px; padding: 12px; border: 2px solid <?= $img->is_main ? '#7938a4' : '#e5e7eb' ?>; min-width: 130px;">
                <img src="<?= $img->image_path ?>" style="width: 100px; height: 140px; object-fit: cover; border-radius: 8px; display: block; margin-bottom: 10px;">
                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: nowrap;">
                    <?php if (!$img->is_main): ?>
                    <a href="<?= \yii\helpers\Url::to(['set-main-image', 'id' => $img->id]) ?>" class="btn-sm btn-outline" style="background: #f0e6f5; border-color: #7938a4; color: #7938a4; font-size: 11px; padding: 4px 8px;" data-method="post">Сделать главной</a>
                    <?php else: ?>
                    <span style="background: #7938a4; color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px;">Главная</span>
                    <?php endif; ?>
                    <a href="<?= \yii\helpers\Url::to(['delete-image', 'id' => $img->id]) ?>" class="btn-sm btn-outline" style="color: #dc2626; border-color: #fecaca; font-size: 11px; padding: 4px 8px;" data-confirm="Удалить изображение?" data-method="post">Удалить</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <small style="color: #64748b;">← Листайте вправо, чтобы увидеть все изображения. Фиолетовой рамкой отмечено главное изображение →</small>
</div>
<?php endif; ?>

<div style="display: flex; gap: 16px; margin-top: 32px;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn-primary']) ?>
    <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-primary" style="background: #6b7280;">Отмена</a>
</div>

<?php ActiveForm::end(); ?>