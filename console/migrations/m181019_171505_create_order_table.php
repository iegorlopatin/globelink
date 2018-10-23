<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m181019_171505_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'coffee_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'amount_to_pay' => $this->float()->notNull(),
            'status' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_order_user_id', 'order', 'user_id');
        $this->addForeignKey('fk_order_user_id', 'order', 'user_id',
            'user', 'id', 'CASCADE');

        $this->createIndex('index_order_coffee_id', 'order', 'coffee_id');
        $this->addForeignKey('fk_order_coffee_id', 'order', 'coffee_id',
            'coffee', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('index_order_user_id', 'order');
        $this->dropForeignKey('fk_order_user_id', 'order');
        $this->dropIndex('index_order_coffee_id', 'order');
        $this->dropForeignKey('fk_order_coffee_id', 'order');
        $this->dropTable('order');
    }
}
