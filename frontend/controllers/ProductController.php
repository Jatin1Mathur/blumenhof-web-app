<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Product;
use common\models\ProductCategory;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ProductController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['viewCatalog'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['manageCatalog'],
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
        $products = Product::find()->all();
        return $this->render('index', ['products' => $products]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate() && $model->uploadImage() && $model->save(false)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate() && $model->uploadImage() && $model->save(false)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Product
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested product does not exist.');
    }
}
