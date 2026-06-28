<?php

declare(strict_types=1);

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class FeatureList extends Widget
{
    /** @var array<int, string> */
    public array $items = [];

    public function run(): string
    {
        $items = array_map(static fn(string $item): string => Html::tag('li', Html::encode($item)), $this->items);

        return Html::tag('ul', implode('', $items), ['class' => 'about-check-list']);
    }
}
