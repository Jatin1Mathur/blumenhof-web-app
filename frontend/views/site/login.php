<?php

declare(strict_types=1);

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LoginForm $model */

$this->title = 'Login';
?>

<section class="auth-page">
    <div class="container-fluid px-4">

        <div class="auth-breadcrumb">
            <?= Html::a('Home', ['/site/index']) ?>
            <span>/</span>
            <span>Login to blumenHof</span>
        </div>

        <div class="auth-wrapper">
            <div class="auth-card">

                <aside class="auth-visual">
                    <span class="auth-visual-badge">
                        Secure staff access
                    </span>

                    <div class="auth-visual-logo">
                        <?= Html::img('@web/images/blumenhof-logo.svg', [
                            'alt' => 'BlumenHof',
                            'class' => 'auth-visual-logo-img',
                        ]) ?>
                    </div>

                    <div class="auth-visual-content">
                        <h1>Welcome back</h1>

                        <p>
                            Access the central workspace for customers,
                            orders, inventory and production.
                        </p>

                        <ul class="auth-visual-points">
                            <li>Manage customers and orders</li>
                            <li>Monitor inventory and production</li>
                            <li>Secure role-based access</li>
                        </ul>
                    </div>
                </aside>

                <main class="auth-form-panel">
                    <header class="auth-form-header">
                        <span class="auth-eyebrow">
                            Account access
                        </span>

                        <h2>Login to blumenHof</h2>

                        <p>
                            Enter your username and password to continue.
                        </p>
                    </header>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => [
                            'class' => 'auth-form',
                        ],
                        'fieldConfig' => [
                            'template' =>
                                "{label}\n"
                                . "<div class=\"auth-field-wrap\">"
                                . "{input}"
                                . "</div>\n"
                                . "{error}",
                            'labelOptions' => [
                                'class' => 'auth-label',
                            ],
                            'errorOptions' => [
                                'class' => 'auth-error',
                            ],
                        ],
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'autocomplete' => 'username',
                        'class' => 'form-control auth-input',
                        'placeholder' => 'Enter your username',
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'autocomplete' => 'current-password',
                        'class' => 'form-control auth-input',
                        'placeholder' => 'Enter your password',
                    ]) ?>

                    <div class="auth-form-options">
                        <?= $form->field($model, 'rememberMe', [
                            'template' =>
                                "<div class=\"form-check auth-check\">"
                                . "{input} {label}"
                                . "</div>\n"
                                . "{error}",
                            'labelOptions' => [
                                'class' =>
                                    'form-check-label auth-check-label',
                            ],
                        ])->checkbox([
                            'class' => 'form-check-input',
                        ], false) ?>
                    </div>

                    <?= Html::submitButton('Login', [
                        'class' => 'btn auth-login-btn',
                        'name' => 'login-button',
                    ]) ?>

                    <div class="auth-links">
                        <?= Html::a(
                            'Forgot your password?',
                            ['/site/request-password-reset']
                        ) ?>

                        <span aria-hidden="true">•</span>

                        <?= Html::a(
                            'Resend verification email',
                            ['/site/resend-verification-email']
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </main>

            </div>
        </div>

    </div>
</section>
