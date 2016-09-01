<?php

use yii\db\Migration;

/**
 * Handles the creation for table `post`.
 */
class m160729_074433_create_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('post', ['id' => 'pk', 'post' => 'text', 'user_id' => 'int']);
        $this->addForeignKey('post_user_id', 'post', 'user_id', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('post');
    }
}
