<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m170110_185740_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_token' => $this->string(),
            'role' => $this->smallInteger()->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(0),
            'last_login_at' => $this->datetime(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);

        $this->createIndex('idx-users-email', 'users', 'email');
        $this->createIndex('idx-users-auth_token', 'users', 'auth_token');
        $this->createIndex('idx-users-status', 'users', 'status');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
