<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="container" style="margin-top: 100px; margin-bottom: 60px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p>
                <?= Html::a('← Назад к поиску', ['assistant/index'], ['class' => 'btn btn-default']) ?>
            </p>
            
            <div class="panel panel-default" style="border-radius: 24px; overflow: hidden;">
                <div class="panel-heading" style="background: #7938a4; color: white; padding: 20px;">
                    <h3 class="panel-title" style="font-size: 20px;">Результат по запросу: <strong>"<?= Html::encode($originalQuery) ?>"</strong></h3>
                </div>
                <div class="panel-body" style="padding: 30px;">
                    
                    <?php if ($dictionaryEntry): ?>
                        <div style="background: #f0e6f5; padding: 24px; border-radius: 20px; margin-bottom: 30px;">
                            <h4 style="color: #7938a4; margin-top: 0; font-size: 22px;"><?= Html::encode($dictionaryEntry->term) ?></h4>
                            
                            <h5 style="margin-top: 20px;">Что это:</h5>
                            <p style="font-size: 16px; line-height: 1.5;"><?= nl2br(Html::encode($dictionaryEntry->definition)) ?></p>
                            
                            <?php if ($dictionaryEntry->usage_example): ?>
                                <h5 style="margin-top: 20px;">Как использовать:</h5>
                                <p style="font-size: 16px; line-height: 1.5;"><?= nl2br(Html::encode($dictionaryEntry->usage_example)) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div style="background: #e8e7e7; padding: 24px; border-radius: 20px; margin-bottom: 30px;">
                            <p style="margin: 0;">Точное совпадение для <strong>"<?= Html::encode($originalQuery) ?>"</strong> не найдено в словаре терминов.</p>
                            <p style="margin-top: 10px;">Но мы нашли книги по вашей теме:</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($books)): ?>
                        <h4 style="margin-bottom: 20px;">Книги по теме</h4>
                        <div class="row">
                            <?php foreach ($books as $book): ?>
                                <div class="col-sm-4" style="margin-bottom: 20px;">
                                    <div style="border: 1px solid #e8e7e7; border-radius: 16px; padding: 16px; background: white; height: 100%;">
                                        <?php if ($book->image): ?>
                                            <img src="<?= Url::to('@web/' . $book->image) ?>" alt="<?= Html::encode($book->title) ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 12px;">
                                        <?php else: ?>
                                            <div style="width: 100%; height: 180px; background: #f0e6f5; border-radius: 12px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center;">📖</div>
                                        <?php endif; ?>
                                        <h5 style="font-size: 16px; margin-bottom: 4px;"><?= Html::encode($book->title) ?></h5>
                                        <p style="font-size: 13px; color: #6c757d; margin-bottom: 12px;"><?= Html::encode($book->author ?? 'Автор не указан') ?></p>
                                        <?= Html::a('Подробнее', ['book/view', 'id' => $book->id], ['class' => 'btn btn-primary btn-sm', 'style' => 'background: #f17000; border: none;']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="background: #fff3cd; padding: 20px; border-radius: 16px;">
                            <p>Книги по этой теме не найдены. Попробуйте другой запрос.</p>
                        </div>
                    <?php endif; ?>
                    
                    <hr style="margin: 30px 0 20px;">
                    <p class="text-muted" style="font-size: 12px;">
                        Нормализованная форма: <strong><?= Html::encode($normalizedQuery) ?></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>