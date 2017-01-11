<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\bootstrap\Nav;

class SiteNavWidget extends Widget
{
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            echo Nav::widget([
                'options' => ['class' => 'nav navbar-nav pull-md-right'],
                'items' => [
                    ['label' => 'Login', 'url' => ['/site/login']],
                ],
            ]);
        } else {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Advertisers', 'url' => ['/advertiser']],
                    ['label' => 'Campaigns', 'url' => ['/campaign']],
                    ['label' => 'Publishers', 'url' => ['/publisher']],
                    ['label' => 'Sites', 'url' => ['/website']],
                    ['label' => 'Transactions', 'url' => ['/transaction']],
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>',
                ],
            ]);
        }
    }
}
