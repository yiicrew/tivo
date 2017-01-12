<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Movie;

class MovieController extends Controller
{
    public $layout = 'column2';

    public function actionView($title)
    {
        $id = preg_match('/(\d+)$/', $title);
        $movie = Movie::findOne($id);
        return $this->render('view', [
            'movie' => $movie,
        ]);
    }
}
