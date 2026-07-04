<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['salesEmployee', 'financialEmployee', 'manager', 'owner', 'admin'],
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
        $orders = Order::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', ['orders' => $orders]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Order();
        $model->user_id = Yii::$app->user->id;

        $itemModels = [new OrderItem()];

        if ($model->load(Yii::$app->request->post())) {
            $itemModels = $this->createOrderItems(Yii::$app->request->post('OrderItem', []));

            if ($model->validate() && $this->validateItems($itemModels)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        foreach ($itemModels as $itemModel) {
                            $itemModel->order_id = $model->id;
                            $itemModel->save(false);
                        }
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $transaction->rollBack();
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), __METHOD__);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'itemModels' => $itemModels,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $itemModels = $model->items ?: [new OrderItem()];

        if ($model->load(Yii::$app->request->post())) {
            $oldItemIds = array_map(static fn ($item) => $item->id, $itemModels);
            $itemModels = $this->createOrderItems(Yii::$app->request->post('OrderItem', []));

            if ($model->validate() && $this->validateItems($itemModels)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        OrderItem::deleteAll(['id' => $oldItemIds]);
                        foreach ($itemModels as $itemModel) {
                            $itemModel->order_id = $model->id;
                            $itemModel->save(false);
                        }
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $transaction->rollBack();
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), __METHOD__);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'itemModels' => $itemModels,
        ]);
    }

    public function actionChangeStatus(int $id)
    {
        $model = $this->findModel($id);
        $newStatus = Yii::$app->request->post('status');

        if ($newStatus !== null && $model->transitionTo($newStatus)) {
            Yii::$app->session->setFlash('success', "Order status changed to {$newStatus}.");
        } else {
            $errors = $model->getErrors('status');
            Yii::$app->session->setFlash('error', $errors ? implode(' ', $errors) : 'Unable to change status.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function createOrderItems(array $postedItems): array
    {
        $items = [];
        foreach ($postedItems as $itemData) {
            $item = new OrderItem();
            $item->load($itemData, '');
            $items[] = $item;
        }
        return $items ?: [new OrderItem()];
    }

    protected function validateItems(array $itemModels): bool
    {
        $valid = true;
        foreach ($itemModels as $itemModel) {
            $valid = $itemModel->validate() && $valid;
        }
        return $valid;
    }

    protected function findModel(int $id): Order
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested order does not exist.');
    }
}
