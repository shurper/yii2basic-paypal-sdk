<?php

use yii\db\Migration;
use yii\db\Schema;

class m170319_110612_payments_table extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица payments
        $this->createTable('{{%payments}}', [
            'id' => Schema::TYPE_PK,
            'payment_id' => Schema::TYPE_STRING . '(32) NOT NULL',
            'status' => Schema::TYPE_SMALLINT .  '(2) NOT NULL DEFAULT 0',
            'user_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'user_name' => Schema::TYPE_STRING.'(255) NULL DEFAULT NULL',
        ], $tableOptions);

    }

    public function down()
    {

        $this->dropTable('{{%payments}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
