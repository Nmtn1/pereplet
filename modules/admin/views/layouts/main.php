<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | Админ-панель Переплёт</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php $this->head() ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1a1a2e;
        }
        
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 65px;
            background: linear-gradient(135deg, #5a2a7c 0%, #7938a4 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: white;
        }
        
        .logo span {
            color: #c084fc;
        }
        
        .admin-nav {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .admin-nav a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
        }
        
        .admin-nav a:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-name {
            color: white;
            font-size: 14px;
        }
        
        .admin-user a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 6px;
        }
        
        .admin-user a:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .admin-content {
            margin-top: 65px;
            padding: 30px;
            min-height: calc(100vh - 65px);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.25s ease;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #7938a4, #b87cd6);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(121, 56, 164, 0.15);
            border-color: #e9d4f5;
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 12px;
            letter-spacing: 0.3px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .stat-link {
            color: #7938a4;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stat-link:hover {
            gap: 8px;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3e8ff;
        }
        
        .card-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .btn-primary {
            background: #7938a4;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
        }
        
        .btn-primary:hover {
            background: #5a2a7c;
        }
        
        .btn-sm {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
            margin: 0 3px;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid #d1d5db;
            color: #4b5563;
        }
        
        .btn-outline:hover {
            border-color: #7938a4;
            color: #7938a4;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        th {
            background: #f9fafb;
            font-weight: 600;
            font-size: 13px;
            color: #6b7280;
        }
        
        tr:hover td {
            background: #faf5ff;
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-green {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-orange {
            background: #ffedd5;
            color: #9a3412;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px;
            color: #9ca3af;
        }
        
        .empty-state p:first-child {
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
        }
        
        .page-link {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #4b5563;
            background: white;
            border: 1px solid #e5e7eb;
        }
        
        .page-link.active {
            background: #7938a4;
            color: white;
            border-color: #7938a4;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 13px;
            color: #374151;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #7938a4;
            box-shadow: 0 0 0 3px rgba(121,56,164,0.1);
        }
        
        textarea.form-control {
            resize: vertical;
        }
        
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .col-6 {
            grid-column: span 1;
        }
        
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                display: none;
            }
            .admin-content {
                padding: 20px;
            }
            .row {
                grid-template-columns: 1fr;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<header class="admin-header">
    <div class="logo">ПЕРЕПЛЁТ <span>| Админка</span></div>
    <nav class="admin-nav">
        <a href="<?= Url::to(['/admin/default/index']) ?>">Обзор</a>
        <a href="<?= Url::to(['/admin/book/index']) ?>">Книги</a>
        <a href="<?= Url::to(['/admin/author/index']) ?>">Авторы</a>
        <a href="<?= Url::to(['/admin/publisher/index']) ?>">Издательства</a>
        <a href="<?= Url::to(['/admin/category/index']) ?>">Категории</a>
        <a href="<?= Url::to(['/admin/reviews/index']) ?>">Отзывы</a>
        <a href="<?= Url::to(['/admin/user/index']) ?>">Пользователи</a>
        <a href="<?= Url::to(['/admin/order/index']) ?>">Заказы</a>
    </nav>
    <div class="admin-user">
        <span class="user-name">
            <?php 
                $user = Yii::$app->user->identity;
                $tmp = $user->first_name;
                echo Html::encode($user->first_name ? $user->first_name . ' ' . $user->last_name : $user->email);
            ?>
        </span>
        <a href="<?= Url::home() ?>" target="_blank">На сайт</a>
        <a href="<?= Url::to(['/site/logout']) ?>" data-method="post">Выйти</a>
    </div>
</header>

<main class="admin-content">
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>