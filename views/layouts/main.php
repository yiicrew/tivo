<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\widgets\SiteNavbar;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- styles -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="http://vjs.zencdn.net/5.8.8/video-js.css" rel="stylesheet">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= SiteNavbar::widget() ?>
    <?= $content ?>
</div>

<footer class="footer bg-inverse">
    <div class="container">
        <div class="footer__nav row">
            <div class="nav-list col-lg-2">
                <h3 class="nav-list__title">Movies</h3>
                <ul class="nav nav-list__items">
                    <li class="nav-item"><a class="nav-link" href="#">Action</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">History</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Thriller</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Science Fiction</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Darama</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Documentry</a></li>
                </ul>
            </div>

            <div class="nav-list col-lg-2">
                <h3 class="nav-list__title">TV-Series</h3>
                <ul class="nav nav-list__items">
                    <li class="nav-item"><a class="nav-link" href="#">United States</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Korea</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">China</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Taiwan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">United Kingdom</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Netherlands</a></li>
                </ul>

            </div>

            <div class="nav-list col-lg-2">
                <h3 class="nav-list__title">Tivo</h3>
                <ul class="nav nav-list__items">
                    <li class="nav-item"><a class="nav-link" href="#">Movies</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Top IMDB</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">DMCA</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Privacy Policy</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Advertising</a></li>
                </ul>
            </div>

            <div class="intro col-lg-6">
                <h3 class="intro__title">About Tivo</h3>
                <p class="intro__text">
                    Watch free online movies, here you can watch movies online in high quality, 1080p for free without annoying advertising and download movie, just come and enjoy your movies.
                </p>
            </div>
        </div>
        <div class="footer__info clearfix">
            <p class="copyright pull-lg-left">&copy; <?= Yii::$app->name ?> <?= date('Y') ?>. All rights reserved.</p>
            <p class="disclaimer pull-lg-right">
                Disclaimer: This site does not store any files on its server. All contents are provided by non-affiliated third parties.
            </p>
        </div>
    </div>
</footer>

    <!-- scripts -->
  <script src="http://vjs.zencdn.net/5.8.8/video.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
