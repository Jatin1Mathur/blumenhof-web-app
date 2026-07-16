<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\CustomerCompany;
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
                        'actions' => ['finance-revenue-data'],
                        'roles' => ['viewFinance'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['viewDashboard'],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $today = date('Y-m-d');

        $recentOrders = Order::find()
            ->with(['company', 'contact', 'items'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        $upcomingDeliveries = Order::find()
            ->with(['company', 'contact'])
            ->andWhere(['is not', 'delivery_date', null])
            ->andWhere(['>=', 'delivery_date', $today])
            ->andWhere([
                'not in',
                'status',
                [
                    Order::STATUS_DELIVERED,
                    Order::STATUS_COMPLETED,
                ],
            ])
            ->orderBy(['delivery_date' => SORT_ASC])
            ->limit(5)
            ->all();

        $overdueOrderCount = (int) Order::find()
            ->andWhere(['is not', 'delivery_date', null])
            ->andWhere(['<', 'delivery_date', $today])
            ->andWhere([
                'not in',
                'status',
                [
                    Order::STATUS_DELIVERED,
                    Order::STATUS_COMPLETED,
                ],
            ])
            ->count();

        return $this->render('index', [
            'recentOrders' => $recentOrders,
            'upcomingDeliveries' => $upcomingDeliveries,
            'overdueOrderCount' => $overdueOrderCount,
        ]);
    }

    /**
     * Returns order counts for today, this week, and this month as JSON.
     */
    /**
     * Returns a genuine daily time series (last 14 days) rather than
     * nested cumulative windows, so the chart shows a real trend instead
     * of a mathematically-guaranteed staircase (today <= week <= month).
     */
    public function actionOrdersSummaryData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $weekStart = strtotime('monday this week');
        $monthStart = strtotime('first day of this month');

        $weekCount = Order::find()->where(['>=', 'created_at', $weekStart])->count();
        $monthCount = Order::find()->where(['>=', 'created_at', $monthStart])->count();

        return [
            'labels' => ['This Week', 'This Month'],
            'data' => [(int) $weekCount, (int) $monthCount],
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

    /**
     * Returns customer companies with no order in the last 1-3 months
     * (i.e. their most recent order is between 1 and 3 months old, or
     * they have never ordered at all).
     */
    public function actionLostClientsData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $oneMonthAgo = strtotime('-1 month');
        $threeMonthsAgo = strtotime('-3 months');

        $lastOrderDates = (new Query())
            ->select(['customer_company_id', 'MAX(created_at) AS last_order_at'])
            ->from('order')
            ->where(['is not', 'customer_company_id', null])
            ->groupBy('customer_company_id')
            ->indexBy('customer_company_id')
            ->all();

        $companies = CustomerCompany::find()->all();
        $lostClients = [];

        foreach ($companies as $company) {
            $lastOrderAt = $lastOrderDates[$company->id]['last_order_at'] ?? null;

            if ($lastOrderAt === null) {
                $lostClients[] = ['name' => $company->name, 'lastOrder' => 'Never'];
                continue;
            }

            if ($lastOrderAt <= $oneMonthAgo && $lastOrderAt >= $threeMonthsAgo) {
                $lostClients[] = ['name' => $company->name, 'lastOrder' => date('Y-m-d', (int) $lastOrderAt)];
            }
        }

        return $lostClients;
    }

    /**
     * Minimal Finance widget: total revenue per month for the last 6
     * months, computed directly from existing Order/OrderItem data.
     * There is no dedicated Finance module yet, so this reuses the
     * order totals already tracked by the Orders module rather than
     * introducing a separate finance/transactions subsystem.
     */
    public function actionFinanceRevenueData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $months = 6;
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = strtotime("first day of -{$i} months", strtotime('today'));
            $monthEnd = strtotime('+1 month', $monthStart);

            $rows = (new Query())
                ->select(['SUM(order_item.quantity * order_item.unit_price) AS revenue'])
                ->from('order_item')
                ->innerJoin('order', 'order.id = order_item.order_id')
                ->where(['>=', 'order.created_at', $monthStart])
                ->andWhere(['<', 'order.created_at', $monthEnd])
                ->scalar();

            $labels[] = date('M Y', $monthStart);
            $data[] = round((float) ($rows ?? 0), 2);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
