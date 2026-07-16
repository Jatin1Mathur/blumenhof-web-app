<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\CustomerContact;
use common\models\CustomerCompany;
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
                        'actions' => ['index', 'view', 'invoice'],
                        'roles' => ['viewOrders'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'change-status'],
                        'roles' => ['manageOrders'],
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
        $orders = Order::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', ['orders' => $orders]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Renders a printable invoice for a single order. Uses its own minimal
     * layout (no header/sidebar/footer) so the browser's print/PDF output
     * is clean.
     */
    public function actionInvoice(int $id)
    {
        $this->layout = 'invoice';
        return $this->render('invoice', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Order();
        $model->user_id = Yii::$app->user->id;

        $itemModels = [new OrderItem()];
        $isNewCompanyFirstOrder = false;

        if ($model->load(Yii::$app->request->post())) {
            $itemModels = $this->createOrderItems(Yii::$app->request->post('OrderItem', []));

            $newCompanyData = Yii::$app->request->post('NewCompany', []);
            $newCompany = $this->createNewCompanyIfProvided($newCompanyData);
            if ($newCompany !== null) {
                $model->customer_company_id = $newCompany->id;
                $isNewCompanyFirstOrder = true;
            }

            $newContactData = Yii::$app->request->post('NewContact', []);
            $newContact = $this->createNewContactIfProvided($newContactData);
            if ($newContact !== null) {
                $model->customer_contact_id = $newContact->id;
            }

            if ($model->validate() && $this->validateItems($itemModels)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        foreach ($itemModels as $itemModel) {
                            $itemModel->order_id = $model->id;
                            $itemModel->save(false);
                        }
                        $transaction->commit();

                        if ($isNewCompanyFirstOrder) {
                            Yii::$app->session->setFlash(
                                'success',
                                "Order created — this is {$newCompany->name}'s first order in the system."
                            );
                        }

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
            $oldItemIds = array_values(array_filter(
                array_map(
                    static fn ($item) => $item->id,
                    $itemModels,
                ),
            ));

            $itemModels = $this->createOrderItems(
                Yii::$app->request->post('OrderItem', []),
            );

            if ($model->validate() && $this->validateItems($itemModels)) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$model->save()) {
                        $transaction->rollBack();

                        return $this->render('update', [
                            'model' => $model,
                            'itemModels' => $itemModels,
                        ]);
                    }

                    if ($oldItemIds !== []) {
                        OrderItem::deleteAll(['id' => $oldItemIds]);
                    }

                    foreach ($itemModels as $itemModel) {
                        $itemModel->order_id = $model->id;
                        $itemModel->save(false);
                    }

                    $transaction->commit();

                    Yii::$app->session->setFlash(
                        'success',
                        'Order updated successfully.',
                    );

                    return $this->redirect([
                        'view',
                        'id' => $model->id,
                    ]);
                } catch (\Throwable $exception) {
                    $transaction->rollBack();

                    Yii::error(
                        $exception->getMessage(),
                        __METHOD__,
                    );

                    Yii::$app->session->setFlash(
                        'error',
                        'The order could not be updated.',
                    );
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

    /**
     * If the "New Individual Customer" form fields were filled in, creates
     * a standalone CustomerContact (no company) from them. Returns null if
     * no first/last name was provided, meaning the order should proceed
     * using whatever existing company/contact was selected instead.
     */
    /**
     * If the "New Company" form fields were filled in, creates a
     * CustomerCompany record from them. Returns null if no name was
     * provided, meaning the order should proceed using whatever existing
     * company was selected from the dropdown instead.
     */
    protected function createNewCompanyIfProvided(array $data): ?CustomerCompany
    {
        $name = trim($data['name'] ?? '');

        if ($name === '') {
            return null;
        }

        $company = new CustomerCompany();
        $company->name = $name;
        $company->city = trim($data['city'] ?? '') ?: null;
        $company->country = trim($data['country'] ?? '') ?: null;

        if (!$company->save()) {
            Yii::error('Failed to create new company: ' . json_encode($company->getErrors()), __METHOD__);
            return null;
        }

        return $company;
    }

    protected function createNewContactIfProvided(array $data): ?CustomerContact
    {
        $firstName = trim($data['first_name'] ?? '');
        $lastName = trim($data['last_name'] ?? '');

        if ($firstName === '' && $lastName === '') {
            return null;
        }

        $contact = new CustomerContact();
        $contact->first_name = $firstName;
        $contact->last_name = $lastName;
        $contact->email = trim($data['email'] ?? '') ?: null;
        $contact->phone = trim($data['phone'] ?? '') ?: null;
        $contact->customer_company_id = null;

        if (!$contact->save()) {
            Yii::error('Failed to create new contact: ' . json_encode($contact->getErrors()), __METHOD__);
            return null;
        }

        return $contact;
    }

    protected function validateItems(array $itemModels): bool
    {
        // order_id is intentionally excluded here: it isn't known until the
        // parent Order has been saved (see actionCreate/actionUpdate), so
        // validating it at this stage would always fail for new items.
        $valid = true;
        foreach ($itemModels as $itemModel) {
            $valid = $itemModel->validate(['product_id', 'quantity', 'unit_price']) && $valid;
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
