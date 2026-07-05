<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\InventoryStock;
use common\models\Order;
use common\models\OrderItem;
use common\models\ProductionOrder;
use yii\db\Query;
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
     * Returns order counts for today, this week, and this month as JSON.
     */
    public function actionOrdersSummaryData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $todayStart = strtotime('today');
        $weekStart = strtotime('monday this week');
        $monthStart = strtotime('first day of this month');

        $todayCount = Order::find()->where(['>=', 'created_at', $todayStart])->count();
        $weekCount = Order::find()->where(['>=', 'created_at', $weekStart])->count();
        $monthCount = Order::find()->where(['>=', 'created_at', $monthStart])->count();

        return [
            'labels' => ['Today', 'This Week', 'This Month'],
            'data' => [(int) $todayCount, (int) $weekCount, (int) $monthCount],
        ];
    }

    /**
     * Returns the top 10 products by total quantity ordered, as JSON.
     */
    public function actionTopProductsData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $rows = (new Query())
            ->select(['product.name AS name', 'SUM(order_item.quantity) AS total_quantity'])
            ->from('order_item')
            ->innerJoin('product', 'product.id = order_item.product_id')
            ->groupBy('product.id')
            ->orderBy(['total_quantity' => SORT_DESC])
            ->limit(10)
            ->all();

        return [
            'labels' => array_column($rows, 'name'),
            'data' => array_map('intval', array_column($rows, 'total_quantity')),
        ];
    }

    /**
     * Returns the top 10 customer companies by number of orders, as JSON.
     */
    public function actionTopCustomersData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $rows = (new Query())
            ->select(['customer_company.name AS name', 'COUNT(order.id) AS order_count'])
            ->from('order')
            ->innerJoin('customer_company', 'customer_company.id = order.customer_company_id')
            ->groupBy('customer_company.id')
            ->orderBy(['order_count' => SORT_DESC])
            ->limit(10)
            ->all();

        return [
            'labels' => array_column($rows, 'name'),
            'data' => array_map('intval', array_column($rows, 'order_count')),
        ];
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
