<?php

declare(strict_types=1);

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class SectionTitle extends Widget
{
    public string $title = '';
    public string $subtitle = '';
    public string $align = 'center';

    public function run(): string
    {
        $classes = 'about-small-heading mb-3';
        if ($this->align === 'left') {
            $classes .= ' text-start';
        } elseif ($this->align === 'center') {
            $classes .= ' text-center';
        }

        return Html::tag('div', 
            Html::tag('p', Html::encode($this->subtitle), ['class' => $classes]) .
            Html::tag('h2', Html::encode($this->title), ['class' => 'fw-semibold mb-0']),
            ['class' => 'mb-4']
        );
    }
}
