<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "movies".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $plot
 * @property string $quality
 * @property integer $runtime
 * @property string $release_date
 * @property string $year
 * @property string $rating
 * @property integer $votes
 * @property integer $views
 * @property string $poster
 * @property string $trailer
 * @property integer $type
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Movie extends ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movies';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'plot', 'poster'], 'required'],
            [['user_id', 'runtime', 'votes', 'views', 'type', 'status'], 'integer'],
            [['plot'], 'string'],
            [['release_date', 'created_at', 'updated_at'], 'safe'],
            [['rating'], 'number'],
            [['title', 'quality', 'year', 'poster', 'trailer'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'plot' => 'Plot',
            'quality' => 'Quality',
            'runtime' => 'Runtime',
            'release_date' => 'Release Date',
            'year' => 'Year',
            'rating' => 'Rating',
            'votes' => 'Votes',
            'views' => 'Views',
            'poster' => 'Poster',
            'trailer' => 'Trailer',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return MovieQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MovieQuery(get_called_class());
    }

    public function getViewUrl()
    {
        return '/view';
    }
}
