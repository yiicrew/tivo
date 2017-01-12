<?php
use app\widgets\Breadcrumb;

$this->beginContent('@app/views/layouts/main.php');
?>
<div class="container">
    <?= Breadcrumb::widget([
        'links' => $this->params['breadcrumbs']
    ]) ?>
    <?= $content ?>
</div>
<?php $this->endContent(); ?>