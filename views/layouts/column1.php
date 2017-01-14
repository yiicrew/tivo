<?php
use app\models\Movie;
use yii\widgets\Breadcrumbs;

$this->beginContent('@app/views/layouts/main.php');

$featured = Movie::find()->featured()->all();
?>
<div class="jumbotron">
    <div class="container">
        <div class="movies movies--carousel owl-carousel owl-theme">
        <?php foreach ($featured as $m): ?>
            <?= $this->render('@app/views/movie/_thumbnail', ['movie' => $m]) ?>
        <?php endforeach ?>
        </div>
    </div>
</div>

<div class="container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= $content ?>
</div>
<?php $this->endContent(); ?>