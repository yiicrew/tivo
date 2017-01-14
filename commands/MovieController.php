<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Movie;

/**
 * This command provides conveniences for movies.
 */
class MovieController extends Controller
{
    public function actionIndex()
    {
        echo "please give me an action to do.";
    }

    /**
     * This command is provided as a utillity to create featured movies.
     */
    public function actionFeatured()
    {
        $urls = [
            'http://www.imdb.com/title/tt1663202',
            'http://www.imdb.com/title/tt3659388',
            'http://www.imdb.com/title/tt0499549',
            'http://www.imdb.com/title/tt0120338',
            'http://www.imdb.com/title/tt1010048',
            'http://www.imdb.com/title/tt0383574',
            'http://www.imdb.com/title/tt0371746',
            'http://www.imdb.com/title/tt0800369',
            'http://www.imdb.com/title/tt0458339',
            'http://www.imdb.com/title/tt2015381',
            'http://www.imdb.com/title/tt1877832',
            'http://www.imdb.com/title/tt1570728'
        ];

        $this->fetchUrls($urls);
    }

    public function actionMostPopular()
    {
        $urls = [
            'http://www.imdb.com/title/tt3748528',
            'http://www.imdb.com/title/tt1355644',
            'http://www.imdb.com/title/tt3783958',
            'http://www.imdb.com/title/tt2094766',
            'http://www.imdb.com/title/tt3470600',
            'http://www.imdb.com/title/tt1386697',
            'http://www.imdb.com/title/tt3631112',
            'http://www.imdb.com/title/tt2404435',
            'http://www.imdb.com/title/tt3717252',
            'http://www.imdb.com/title/tt4465564',
            'http://www.imdb.com/title/tt2543164',
            'http://www.imdb.com/title/tt3521164'
        ];

        $this->fetchUrls($urls);
    }

    public function actionMostRated()
    {
        $urls = [
            'http://www.imdb.com/title/tt0111161',
            'http://www.imdb.com/title/tt0068646',
            'http://www.imdb.com/title/tt0071562',
            'http://www.imdb.com/title/tt0468569',
            'http://www.imdb.com/title/tt0050083',
            'http://www.imdb.com/title/tt0108052',
            'http://www.imdb.com/title/tt0110912',
            'http://www.imdb.com/title/tt0167260',
            'http://www.imdb.com/title/tt0060196',
            'http://www.imdb.com/title/tt0137523',
            'http://www.imdb.com/title/tt0080684',
            'http://www.imdb.com/title/tt1375666',
        ];

        $this->fetchUrls($urls);
    }

    private function fetchUrls($urls)
    {
        foreach ($urls as $url) {
            $m = Yii::$app->imdb->getMovie($url);
            $this->saveMovie($m);
        }
    }

    private function saveMovie($m)
    {
        $movie = new Movie;
        $movie->user_id = 1;
        $movie->title = $m->getTitle();
        $movie->plot = $m->getPlot();
        $movie->runtime = (int) $m->getRuntime();
        $movie->poster = $m->getPoster('thumb');
        $movie->release_date = $m->getReleaseDate();
        $movie->status = Movie::STATUS_ACTIVE;
        
        if ($movie->save()) {
            echo $movie->title . PHP_EOL;
            echo $movie->runtime . ' min' . PHP_EOL;
            echo '============ Saved!' . PHP_EOL;
        } else {
            print_r($movie->errors);
            echo PHP_EOL;
        }
    }

    /**
     * This command is used to dump movies table.
     */
    public function actionDump()
    {
        var_dump(Yii::$app->cache->multiGet(['1_campaign_url', '2_campaign_url']));
    }
}
