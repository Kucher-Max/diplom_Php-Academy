<?php

use yii\db\Migration;

/**
 * Handles the dropping for table `user`.
 */
class m160809_105328_drop_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('user');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m160809_105328_drop_user_table cannot be reverted. \n";
        return false;
    }
}
