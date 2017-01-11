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
    /**
     * This command is provided as a utillity to create sample movies.
     */
    public function actionIndex()
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
            'http://www.imdb.com/title/tt1877832'
        ];
        
        foreach ($urls as $url) {
            $m = Yii::$app->imdb->getMovie($url);
            $movie = new Movie;
            $movie->user_id = 1;
            $movie->title = $m->getTitle();
            $movie->plot = $m->getPlot();
            $movie->runtime = (int) $m->getRuntime();
            $movie->poster = $m->getPoster();
            $movie->status = Movie::STATUS_ACTIVE;
            
            if ($movie->save()) {
                echo $movie->title . PHP_EOL;
                echo $movie->runtime . ' min' . PHP_EOL;
                echo "============ Saved!" . PHP_EOL;
            } else {
                print_r($movie->errors);
                echo PHP_EOL;
            }
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
