<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\CustomerCompany;
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
}
