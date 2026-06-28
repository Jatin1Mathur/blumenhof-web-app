<?php

declare(strict_types=1);

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ServiceCard extends Widget
{
    public string $icon = '';
    public string $title = '';
    public string $body = '';

    public function run(): string
    {
        return Html::tag('div',
            Html::tag('div', Html::encode($this->icon), ['class' => 'service-icon']) .
            Html::tag('h3', Html::encode($this->title), []) .
            Html::tag('p', Html::encode($this->body), []),
            ['class' => 'service-minimal-card']
        );
    }
}
