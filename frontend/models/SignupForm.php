<?php

declare(strict_types=1);

namespace frontend\models;

use common\models\User;
use Throwable;
use Yii;
use yii\base\Model;
use yii\mail\MailerInterface;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public string $username = '';
    public string $email = '';
    public string $password = '';

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'validateUsernameForSignup'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'validateEmailForSignup'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Active accounts must keep a unique email address.
     * Inactive accounts can use the same email to resend verification.
     */
    public function validateEmailForSignup(string $attribute): void
    {
        if ($this->hasErrors($attribute)) {
            return;
        }

        $user = User::find()
            ->where(['email' => trim($this->email)])
            ->one();

        if (
            $user !== null
            && (int) $user->status !== User::STATUS_INACTIVE
        ) {
            $this->addError(
                $attribute,
                'This email address has already been taken.',
            );
        }
    }

    /**
     * Ignore the username uniqueness error when the submitted email belongs
     * to an existing inactive account. The email will receive a new link.
     */
    public function validateUsernameForSignup(string $attribute): void
    {
        if ($this->hasErrors($attribute)) {
            return;
        }

        $inactiveUserWithSameEmail = User::find()
            ->where([
                'email' => trim($this->email),
                'status' => User::STATUS_INACTIVE,
            ])
            ->exists();

        if ($inactiveUserWithSameEmail) {
            return;
        }

        $usernameExists = User::find()
            ->where(['username' => trim($this->username)])
            ->exists();

        if ($usernameExists) {
            $this->addError(
                $attribute,
                'This username has already been taken.',
            );
        }
    }

    /**
     * Creates a user or resends verification for an inactive user.
     *
     * @param MailerInterface $mailer the mailer component.
     * @param string $supportEmail the support email address.
     * @param string $appName the application name.
     *
     * @return bool|null whether signup or resend was successful.
     */
    public function signup(
        MailerInterface $mailer,
        string $supportEmail,
        string $appName,
    ): bool|null {
        if (!$this->validate()) {
            return null;
        }

        /*
         * The email already exists, but the account is not verified.
         * Do not create another database row.
         * Create a fresh verification token and send a new email.
         */
        $existingUser = User::find()
            ->where([
                'email' => trim($this->email),
                'status' => User::STATUS_INACTIVE,
            ])
            ->one();

        if ($existingUser !== null) {
            $existingUser->generateEmailVerificationToken();

            if (!$existingUser->save(false)) {
                $this->addError(
                    'email',
                    'The verification link could not be renewed. Please try again.',
                );

                return false;
            }

            if (
                !$this->sendEmail(
                    $mailer,
                    $existingUser,
                    $supportEmail,
                    $appName,
                )
            ) {
                $this->addError(
                    'email',
                    'The verification email could not be sent. Please try again.',
                );

                return false;
            }

            return true;
        }

        /*
         * No account exists with this email, so create a new inactive user.
         */
        $user = new User();

        $user->username = trim($this->username);
        $user->email = trim($this->email);
        $user->status = User::STATUS_INACTIVE;

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if (!$user->save()) {
            $this->addErrors($user->getErrors());

            return false;
        }

        $guestRole = Yii::$app->authManager->getRole('guest');

        if ($guestRole !== null) {
            Yii::$app->authManager->assign(
                $guestRole,
                $user->id,
            );
        }

        if (
            !$this->sendEmail(
                $mailer,
                $user,
                $supportEmail,
                $appName,
            )
        ) {
            $this->addError(
                'email',
                'Your account was created, but the verification email could not be sent. Submit the same email again to resend it.',
            );

            return false;
        }

        return true;
    }

    /**
     * Sends the verification email.
     */
    protected function sendEmail(
        MailerInterface $mailer,
        User $user,
        string $supportEmail,
        string $appName,
    ): bool {
        try {
            return Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'emailVerify-html',
                        'text' => 'emailVerify-text',
                    ],
                    ['user' => $user],
                )
                ->setFrom([
                    $supportEmail => $appName . ' robot',
                ])
                ->setTo($user->email)
                ->setSubject(
                    'Account registration at ' . $appName,
                )
                ->send();
        } catch (Throwable $exception) {
            Yii::error($exception, __METHOD__);

            return false;
        }
    }
}
