<?php

use yii\db\Migration;

/**
 * Handles the creation of table `servers`.
 */
class m170318_192523_create_servers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('servers', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);

        $this->createIndex('idx-servers-status', 'servers', 'status');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('servers');
    }
}
