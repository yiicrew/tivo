<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

class SiteNavbar extends Widget
{
    public function run()
    {
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-dark navbar-fixed-top bg-inverse',
            ],
        ]);
        $this->siteNav();
        NavBar::end();
    }

    public function siteNav()
    {
        $items = [];
        if (Yii::$app->user->isGuest) {
            $items[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $items[] = '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton('Logout', ['class' => 'btn btn-link logout'])
                    . Html::endForm()
                    . '</li>';
        }

        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav pull-md-right'],
            'items' => $items,
        ]);
    }
}
