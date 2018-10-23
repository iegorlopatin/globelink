<?php

use yii\db\Migration;

/**
 * Handles the creation of table `coffee`.
 */
class m181019_170349_create_coffee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('coffee', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'intensity' => $this->string(255)->notNull(),
            'price' => $this->float()->notNull(),
            'stock' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('coffee');
    }
}
