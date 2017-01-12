<?php

/* @var $this yii\web\View */

use app\models\Movie;

$this->title = Yii::$app->params['appName'];

$featured = Movie::find()->featured()->all();
$mostRated = Movie::find()->mostRated()->all();
$mostPopular = Movie::find()->mostPopular()->all();
?>
<div class="site-index">
    <?= $this->render('@app/views/shared/_widget', [
        'title' => 'Featured Movies',
        'movies' => $featured
    ]) ?>

    <?= $this->render('@app/views/shared/_widget', [
        'title' => 'Most Rated',
        'movies' => $mostRated
    ]) ?>

    <?= $this->render('@app/views/shared/_widget', [
        'title' => 'Most Popular',
        'movies' => $mostPopular
    ]) ?>
</div>
