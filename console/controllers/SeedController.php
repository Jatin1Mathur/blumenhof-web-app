<?php

declare(strict_types=1);

namespace console\controllers;

use common\models\CustomerCategory;
use common\models\CustomerCompany;
use common\models\CustomerContact;
use common\models\InventoryStock;
use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\ProductCategory;
use common\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

class SeedController extends Controller
{
    /**
     * Seeds realistic florist demo data: products, categories, customers, and orders.
     *
     * Usage: php yii seed/demo
     */
    public function actionDemo(): int
    {
        $this->stdout("Seeding demo data...\n");
        $categories = $this->seedCategories();
        $products = $this->seedProducts($categories);
        $customerCategories = $this->seedCustomerCategories();
        $companies = $this->seedCompanies($customerCategories);
        $contacts = $this->seedContacts($companies);
        $this->seedInventoryStock($products);
        $this->seedOrders($companies, $contacts, $products);
        $this->stdout("Demo data seeded successfully.\n");
        return ExitCode::OK;
    }

    protected function seedCategories(): array
    {
        $names = ['Bridal', 'Sympathy', 'Birthday', 'Seasonal'];
        $categories = [];

        foreach ($names as $name) {
            $category = ProductCategory::findOne(['name' => $name]) ?? new ProductCategory();
            $category->name = $name;
            $category->description = $name . ' arrangements';
            $category->save();
            $categories[] = $category;
        }

        $this->stdout("  Seeded " . count($categories) . " product categories.\n");

        return $categories;
    }

    protected function seedProducts(array $categories): array
    {
        $sampleProducts = [
            ['Red Rose Bouquet', 29.99, false],
            ['White Lily Arrangement', 34.99, true],
            ['Sunflower Bunch', 19.99, true],
            ['Orchid Centerpiece', 49.99, false],
            ['Mixed Seasonal Bouquet', 24.99, true],
            ['Wedding Bridal Bouquet', 89.99, true],
            ['Sympathy Wreath', 59.99, false],
            ['Birthday Balloon Bouquet', 15.99, false],
        ];

        $products = [];
        foreach ($sampleProducts as $index => [$name, $price, $isPerishable]) {
            $product = Product::findOne(['name' => $name]) ?? new Product();
            $product->name = $name;
            $product->price = $price;
            $product->is_perishable = $isPerishable;
            $product->product_category_id = $categories[$index % count($categories)]->id;
            $product->save();
            $products[] = $product;
        }

        $this->stdout("  Seeded " . count($products) . " products.\n");

        return $products;
    }

    protected function seedCustomerCategories(): array
    {
        $names = [
            'Hotel' => 'Hotel and hospitality clients',
            'Event Planner' => 'Event planning and coordination businesses',
            'Corporate' => 'Corporate and business clients',
            'Individual' => 'Individual/private customers',
        ];
        $categories = [];

        foreach ($names as $name => $description) {
            $category = CustomerCategory::findOne(['name' => $name]) ?? new CustomerCategory();
            $category->name = $name;
            $category->description = $description;
            $category->save();
            $categories[] = $category;
        }

        $this->stdout("  Seeded " . count($categories) . " customer categories.\n");

        return $categories;
    }

    protected function seedCompanies(array $customerCategories): array
    {
        $sampleCompanies = [
            ['Grand Hotel Hof', 'Hof', 'Germany', 'Hotel'],
            ['Elegant Events GmbH', 'Munich', 'Germany', 'Event Planner'],
            ['Corporate Blooms AG', 'Berlin', 'Germany', 'Corporate'],
        ];

        $categoryByName = [];
        foreach ($customerCategories as $category) {
            $categoryByName[$category->name] = $category->id;
        }

        $companies = [];
        foreach ($sampleCompanies as [$name, $city, $country, $categoryName]) {
            $company = CustomerCompany::findOne(['name' => $name]) ?? new CustomerCompany();
            $company->name = $name;
            $company->city = $city;
            $company->country = $country;
            $company->customer_category_id = $categoryByName[$categoryName] ?? null;
            $company->save();
            $companies[] = $company;
        }

        $this->stdout("  Seeded " . count($companies) . " customer companies.\n");

        return $companies;
    }

    protected function seedContacts(array $companies): array
    {
        $sampleContacts = [
            ['Anna', 'Schmidt', $companies[0]->id ?? null],
            ['Max', 'Weber', $companies[1]->id ?? null],
            ['Julia', 'Fischer', null],
        ];

        $contacts = [];
        foreach ($sampleContacts as [$firstName, $lastName, $companyId]) {
            $contact = CustomerContact::findOne(['first_name' => $firstName, 'last_name' => $lastName]) ?? new CustomerContact();
            $contact->first_name = $firstName;
            $contact->last_name = $lastName;
            $contact->customer_company_id = $companyId;
            $contact->save();
            $contacts[] = $contact;
        }

        $this->stdout("  Seeded " . count($contacts) . " customer contacts.\n");

        return $contacts;
    }

    protected function seedInventoryStock(array $products): void
    {
        $count = 0;
        foreach ($products as $index => $product) {
            $stock = InventoryStock::findOne(['product_id' => $product->id]) ?? new InventoryStock();
            $stock->product_id = $product->id;
            // Vary quantities so both healthy and low-stock states are demonstrated.
            $stock->quantity = ($index % 3 === 0) ? 3 : 25;
            $stock->low_stock_threshold = 5;
            $stock->save();
            $count++;
        }

        $this->stdout("  Seeded {$count} inventory stock records.\n");
    }

    protected function seedOrders(array $companies, array $contacts, array $products): void
    {
        $statuses = Order::statusList();
        $count = 0;

        // Use the first available user as the order owner, rather than a
        // hardcoded id that may not exist in every environment.
        $ownerUserId = User::find()->orderBy(['id' => SORT_ASC])->scalar();
        if ($ownerUserId === false) {
            $this->stdout("  Skipped order seeding: no users exist yet.\n");
            return;
        }

        for ($i = 0; $i < 6; $i++) {
            $order = new Order();
            $order->user_id = $ownerUserId;
            $order->customer_company_id = $companies[$i % count($companies)]->id;
            $order->status = $statuses[$i % count($statuses)];
            $order->delivery_date = date('Y-m-d', strtotime('+' . ($i + 1) . ' days'));
            if ($order->save()) {
                $itemCount = random_int(1, 3);
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products[array_rand($products)];
                    $item = new OrderItem();
                    $item->order_id = $order->id;
                    $item->product_id = $product->id;
                    $item->quantity = random_int(1, 5);
                    $item->unit_price = $product->price;
                    $item->save();
                }
                $count++;
            }
        }

        $this->stdout("  Seeded {$count} orders with items.\n");
    }
}
