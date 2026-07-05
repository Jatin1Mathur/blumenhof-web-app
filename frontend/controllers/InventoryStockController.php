<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\InventoryStock;
use common\models\Product;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class InventoryStockController extends Controller
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
        $stocks = InventoryStock::find()->all();
        return $this->render('index', ['stocks' => $stocks]);
    }

    public function actionCreate()
    {
        $model = new InventoryStock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): InventoryStock
    {
        if (($model = InventoryStock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested stock record does not exist.');
    }
}
