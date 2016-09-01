<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160729_074314_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', ['id' => 'pk', 'password' => 'string NOT NULL', 'email' => 'string NOT NULL']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
