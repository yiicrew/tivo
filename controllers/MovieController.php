<?php

namespace app\controllers;

use app\models\Movie;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MovieController extends Controller
{
    public $layout = 'column2';

    public function actionView($title, $id)
    {
        $movie = Movie::findOne($id);
        if ($movie === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $movie->updateCounters(['views' => 1]);

        return $this->render('view', [
            'movie' => $movie,
        ]);
    }
}
