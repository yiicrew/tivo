<?php

use yii\db\Migration;

/**
 * Handles the creation of table `movies`.
 */
class m170110_190218_create_movies_table extends Migration
{
    public function up()
    {
        $this->createTable('movies', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'plot' => $this->text()->notNull(),
            'quality' => $this->string(),
            'runtime' => $this->integer()->defaultValue(0),
            'release_date' => $this->datetime(),
            'year' => $this->string(),
            'rating' => $this->decimal()->defaultValue(0.0),
            'votes' => $this->integer()->defaultValue(0),
            'views' => $this->integer()->defaultValue(0),
            'poster' => $this->string()->notNull(),
            'trailer' => $this->string(),
            'type' => $this->smallInteger(),
            'status' => $this->smallInteger(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createIndex('idx-movies-user_id', 'movies', 'user_id');
        $this->addForeignKey(
            'fk-movies-user_id',
            'movies',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-movies-title', 'movies', 'title');
        $this->createIndex('idx-movies-rating', 'movies', 'rating');
        $this->createIndex('idx-movies-views', 'movies', 'views');
        $this->createIndex('idx-movies-release_date', 'movies', 'release_date');
        $this->createIndex('idx-movies-type', 'movies', 'type');
        $this->createIndex('idx-movies-status', 'movies', 'status');
        $this->createIndex('idx-movies-created_at', 'movies', 'created_at');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-movies-user_id', 'movies');

        $this->dropTable('movies');
    }
}
