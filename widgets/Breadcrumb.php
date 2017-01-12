<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

class Breadcrumb extends Breadcrumbs
{
    public $itemTemplate = '<li class="breadcrumb-item">{link}</li>';
    public $activeItemTemplate = '<li class="breadcrumb-item active">{link}</li>';
}
