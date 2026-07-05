<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
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
}
