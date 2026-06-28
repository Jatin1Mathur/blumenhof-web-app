<?php

declare(strict_types=1);

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class InfoCard extends Widget
{
    public string $title = '';
    public string $body = '';
    public string $number = '';

    public function run(): string
    {
        return Html::tag('div',
            Html::tag('p', Html::encode($this->number), ['class' => 'section-number']) .
            Html::tag('h2', Html::encode($this->title), []) .
            Html::tag('p', Html::encode($this->body), []),
            ['class' => 'about-info-block']
        );
    }
}
