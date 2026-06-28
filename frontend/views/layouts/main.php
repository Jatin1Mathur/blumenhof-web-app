<?php
declare(strict_types=1);

use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column min-vh-100">
<?php $this->beginBody() ?>

<?= $this->render('_header') ?>

<main id="main" class="flex-shrink-0" role="main">
    <?php if (!empty($this->params['breadcrumbs'])): ?>
        <div class="container mt-3">
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        </div>
    <?php endif; ?>

    <?= $content ?>
</main>

<?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
