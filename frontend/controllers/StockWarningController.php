<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\InventoryStock;
use yii\filters\AccessControl;
use yii\web\Controller;

class StockWarningController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['inventoryEmployee', 'manager', 'owner', 'admin'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $warnings = InventoryStock::findNeedingWarning();
        return $this->render('index', ['warnings' => $warnings]);
    }
}
