<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\InventoryStock;
use common\models\ProductionOrder;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class DashboardController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager', 'owner', 'admin'],
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
        return $this->render('index');
    }

    /**
     * Returns a stock/production overview: count of low-stock items,
     * expiring/expired items, and production tasks by status.
     */
    public function actionStockProductionData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $allStock = InventoryStock::find()->all();
        $lowStockCount = 0;
        $expiringCount = 0;
        $expiredCount = 0;

        foreach ($allStock as $stock) {
            if ($stock->isLowStock()) {
                $lowStockCount++;
            }
            if ($stock->isExpired()) {
                $expiredCount++;
            } elseif ($stock->isExpiringSoon()) {
                $expiringCount++;
            }
        }

        $pendingCount = ProductionOrder::find()->where(['status' => ProductionOrder::STATUS_PENDING])->count();
        $inProgressCount = ProductionOrder::find()->where(['status' => ProductionOrder::STATUS_IN_PROGRESS])->count();
        $doneCount = ProductionOrder::find()->where(['status' => ProductionOrder::STATUS_DONE])->count();

        return [
            'stock' => [
                'labels' => ['Low Stock', 'Expiring Soon', 'Expired'],
                'data' => [$lowStockCount, $expiringCount, $expiredCount],
            ],
            'production' => [
                'labels' => ['Pending', 'In Progress', 'Done'],
                'data' => [(int) $pendingCount, (int) $inProgressCount, (int) $doneCount],
            ],
        ];
    }
}
