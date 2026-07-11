<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\InventoryStock;
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
                        'actions' => ['index', 'batches', 'view'],
                        'roles' => ['viewProduction'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['generate', 'change-status', 'update', 'delete'],
                        'roles' => ['manageProduction'],
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
        $productionOrders = ProductionOrder::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', ['productionOrders' => $productionOrders]);
    }

    /**
     * Groups pending/in-progress production tasks by batch_date for same-day prep runs.
     */
    public function actionBatches()
    {
        $productionOrders = ProductionOrder::find()
            ->where(['in', 'status', [ProductionOrder::STATUS_PENDING, ProductionOrder::STATUS_IN_PROGRESS]])
            ->orderBy(['batch_date' => SORT_ASC])
            ->all();

        $batches = [];
        foreach ($productionOrders as $po) {
            $key = $po->batch_date ?? 'Unscheduled';
            $batches[$key][] = $po;
        }

        return $this->render('batches', ['batches' => $batches]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Generates a production task for a confirmed order that doesn't have one yet.
     * Automatically decides stock vs produce based on inventory availability,
     * and assigns a batch date (today's date, or the order's delivery date if
     * that's sooner) for same-day batching.
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
        $productionOrder->source = $this->decideSource($order);
        $productionOrder->batch_date = $order->delivery_date ?? date('Y-m-d');

        if ($productionOrder->save()) {
            Yii::$app->session->setFlash('success', 'Production task generated (source: ' . $productionOrder->source . ').');
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

    /**
     * Decides whether an order can be fulfilled from existing stock, or needs
     * fresh production. If ANY item in the order doesn't have sufficient
     * stock, the whole task is marked as 'produce'.
     */
    protected function decideSource(Order $order): string
    {
        foreach ($order->items as $item) {
            $stock = InventoryStock::findOne(['product_id' => $item->product_id]);

            if ($stock === null || $stock->quantity < $item->quantity) {
                return ProductionOrder::SOURCE_PRODUCE;
            }
        }

        return ProductionOrder::SOURCE_STOCK;
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
