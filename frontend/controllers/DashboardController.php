<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Order;
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
}
