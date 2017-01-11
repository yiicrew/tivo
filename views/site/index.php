<?php

/* @var $this yii\web\View */

use app\models\Movie;

$this->title = Yii::$app->params['appName'];

$featured = Movie::find()->featured()->all();
?>
<div class="site-index">
    <div class="widget">
        <div class="widget__header">
            <h2 class="widget__title">Featured Movies</h2>
        </div>
        <div class="widget__content">
            <div class="card-columns">
            <?php foreach ($featured as $f): ?>
                <div class="card card-block">
                    <?= $this->render('@app/views/movie/_thumbnail', ['movie' => $f]) ?>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
