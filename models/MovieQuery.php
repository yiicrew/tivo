<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Movie]].
 *
 * @see Movie
 */
class MovieQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Movie[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Movie|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function featured()
    {
        return $this->limit(12);
    }

    public function mostRated()
    {
        return $this->offset(12)->limit(12);
    }

    public function mostPopular()
    {
        return $this->offset(24)->limit(12);
    }
}
