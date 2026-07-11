<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\CustomerCompany;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CustomerCompanyController extends Controller
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
                        'roles' => ['viewCrm'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['manageCrm'],
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
        $companies = CustomerCompany::find()->all();
        $individuals = \common\models\CustomerContact::find()
            ->where(['customer_company_id' => null])
            ->all();

        return $this->render('index', [
            'companies' => $companies,
            'individuals' => $individuals,
        ]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new CustomerCompany();

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

    protected function findModel(int $id): CustomerCompany
    {
        if (($model = CustomerCompany::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested company does not exist.');
    }
}
