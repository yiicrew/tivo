<?php

$this->params['breadcrumbs'] = [
    ['label' => $movie->genre, 'url' => $movie->genre],
    ['label' => $movie->title]
];
$this->title = $movie->title;
?>
<div class="player">
    <video class="video-js vjs-16-9" preload="auto" data-setup="{}" controls>
        <source src="http://185.176.192.22/vids/the_blackout_experiments_2016___587407e17d2f7.mp4" type='video/mp4'>
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
    </video>
</div>

<br>

<div class="card card-block">
    <div class="movie movie--single">
        <div class="movie__media">
            <img src="<?= $movie->poster ?>" class="movie__poster">
        </div>
        <div class="movie__content">
            <h2 class="movie__title"><?= $movie->title ?></h2>
            <p class="movie__plot">
                <?= $movie->plot ?>
            </p>
            <div class="movie__attributes">
                <ul class="attributes">
                    <li class="attribute">
                        <b class="attribute__label">Genre:</b>
                        <span class="attribute__value">
                            <a href="#">Action</a>,
                            <a href="#">Adventure</a>,
                            <a href="#">Animation</a>
                        </span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Actor:</b>
                        <span class="attribute__value">
                            <a href="#">Thomas Lennon</a>,
                            <a href="#">Lucas Till</a>,
                            <a href="#">Jane Levy</a>
                        </span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Director:</b>
                        <span class="attribute__value">
                            <a href="#">Chris Wedge</a>
                        </span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Country:</b>
                        <span class="attribute__value">
                            <a href="#">United States</a>
                        </span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Language:</b>
                        <span class="attribute__value">
                            <a href="#">English</a>
                        </span>
                    </li>
                </ul>

                <ul class="attributes">
                    <li class="attribute">
                        <b class="attribute__label">Runtime:</b>
                        <span class="attribute__value"><?= $movie->runtime ?> min</span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Quality:</b>
                        <span class="attribute__value"><?= $movie->quality ?></span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Release Date:</b>
                        <span class="attribute__value"><?= $movie->release_date ?></span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">IMDB Rating:</b>
                        <span class="attribute__value"><?= $movie->rating ?></span>
                    </li>
                    <li class="attribute">
                        <b class="attribute__label">Views:</b>
                        <span class="attribute__value"><?= $movie->views ?></span>
                    </li>
                </ul>
            </div>

            <div class="movie__tags">
                <dl class="tag-list">
                    <dt class="tag-list__label">Keywords: </dt>
                    <dd class="tag">
                        <a href="#" class="tag__link">#Action</a>
                    </dd>
                    <dd class="tag">
                        <a href="#" class="tag__link">#Adventure</a>
                    </dd>
                    <dd class="tag">
                        <a href="#" class="tag__link">#Science Fiction</a>
                    </dd>
                    <dd class="tag">
                        <a href="#" class="tag__link">#Comedy</a>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="card card-block">
    <div class="comments fb-comments"
        data-href="<?= $movie->commentsUrl ?>"
        data-width="100%"
        data-numposts="5">
    </div>
</div>


<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '727243164041505',
            cookie: true,  // enable cookies to allow the server to access
                           // the session
            xfbml: true,  // parse social plugins on this page
            version: 'v2.6' // use graph api version 2.6
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
