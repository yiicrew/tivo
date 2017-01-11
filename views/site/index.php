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
            <div class="movies movies--grid">
            <?php foreach ($featured as $f): ?>
                    <?= $this->render('@app/views/movie/_thumbnail', ['movie' => $f]) ?>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
