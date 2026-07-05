<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Order;
use common\models\ProductionOrder;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProductionOrderController extends Controller
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
        $productionOrders = ProductionOrder::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', ['productionOrders' => $productionOrders]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Generates a production task for a confirmed order that doesn't have one yet.
     */
    public function actionGenerate(int $orderId)
    {
        $order = Order::findOne($orderId);

        if ($order === null) {
            throw new NotFoundHttpException('The requested order does not exist.');
        }

        if ($order->status !== Order::STATUS_CONFIRMED) {
            Yii::$app->session->setFlash('error', 'Only confirmed orders can have a production task generated.');
            return $this->redirect(['order/view', 'id' => $orderId]);
        }

        $existing = ProductionOrder::findOne(['order_id' => $orderId]);
        if ($existing !== null) {
            Yii::$app->session->setFlash('error', 'A production task already exists for this order.');
            return $this->redirect(['view', 'id' => $existing->id]);
        }

        $productionOrder = new ProductionOrder();
        $productionOrder->order_id = $orderId;
        $productionOrder->user_id = Yii::$app->user->id;
        $productionOrder->status = ProductionOrder::STATUS_PENDING;
        $productionOrder->source = ProductionOrder::SOURCE_PRODUCE;

        if ($productionOrder->save()) {
            Yii::$app->session->setFlash('success', 'Production task generated.');
            return $this->redirect(['view', 'id' => $productionOrder->id]);
        }

        Yii::$app->session->setFlash('error', 'Unable to generate production task.');
        return $this->redirect(['order/view', 'id' => $orderId]);
    }

    public function actionChangeStatus(int $id)
    {
        $model = $this->findModel($id);
        $newStatus = Yii::$app->request->post('status');

        if ($newStatus !== null && $model->transitionTo($newStatus)) {
            Yii::$app->session->setFlash('success', "Production task status changed to {$newStatus}.");
        } else {
            $errors = $model->getErrors('status');
            Yii::$app->session->setFlash('error', $errors ? implode(' ', $errors) : 'Unable to change status.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): ProductionOrder
    {
        if (($model = ProductionOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested production order does not exist.');
    }
}
