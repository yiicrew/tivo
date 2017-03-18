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
        $items[] = ['label' => 'Home', 'url' => ['/site/index']];
        $items[] = ['label' => 'Genre', 'url' => ['/movies/genre']];
        $items[] = ['label' => 'Country', 'url' => ['/movies/country']];
        $items[] = ['label' => 'Year', 'url' => ['/movies/country']];
        $items[] = ['label' => 'TV-Shows', 'url' => ['/show/index']];
        $items[] = ['label' => 'Latest', 'url' => ['/show/index']];
        $items[] = ['label' => 'Comming Soon', 'url' => ['/show/index']];
        $items[] = ['label' => 'New Request', 'url' => ['/show/index']];


        /*if (Yii::$app->user->isGuest) {
            $items[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $items[] = '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton('Logout', ['class' => 'btn btn-link logout'])
                    . Html::endForm()
                    . '</li>';
        }*/

        $items[] = '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'search'])
                    . '<div class="form-group form-inline" style="margin:0;">'
                    . Html::textInput('q', '', ['class' => 'form-control search__input'])
                    . Html::submitButton('Search', ['class' => 'btn btn-primary search__button'])
                    . Html::endForm()
                    . '</div>'
                    . '</li>';

        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav pull-md-right'],
            'items' => $items,
        ]);
    }
}
