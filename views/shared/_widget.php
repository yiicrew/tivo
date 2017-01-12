<div class="widget">
    <div class="widget__header">
        <div class="widget__actions">
            <a href="#" class="btn btn-primary btn-sm">
                View more
                <i class="fa fa-caret-right"></i>
            </a>
        </div>
        <h2 class="widget__title"><?= $title ?></h2>
    </div>
    <div class="widget__content">
        <div class="movies movies--grid">
        <?php foreach ($movies as $m): ?>
            <?= $this->render('@app/views/movie/_thumbnail', ['movie' => $m]) ?>
        <?php endforeach ?>
        </div>
    </div>
</div>