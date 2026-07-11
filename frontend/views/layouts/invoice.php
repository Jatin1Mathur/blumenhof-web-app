<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $content */

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            font-family: -apple-system, Helvetica, Arial, sans-serif;
            color: #202020;
            margin: 0;
            padding: 40px;
        }
        @media print {
            body { padding: 0; }
            .invoice-print-btn { display: none; }
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
