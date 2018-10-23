<?php

use yii\db\Migration;

/**
 * Class m181022_182519_populate_database_with_init_data
 */
class m181022_182519_populate_database_with_init_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertUsers();
        $this->insertCoffee();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181022_182519_populate_database_with_init_data cannot be reverted.\n";

        return false;
    }

    private function insertUsers()
    {
        $this->insert('user', [
            'username' => 'First Employee',
            'role' => 'employee',
            'auth_key' => 'oDWCLUbJrfti_jzcIp1Kmu6F6-gWGxwo',
            'password_hash' => '$2y$13$6gk0eI//HR4NmnUpSTPZXOXocLlW6AKiczrhNyHZ4ZtWoSidv9nYu',
            'email' => 'first.employee@coffeeplace.com',
            'status' => '10',
            'created_at' => '1540033113',
            'updated_at' => '1540033113',
        ]);

        $this->insert('user', [
            'username' => 'Admin',
            'role' => 'admin',
            'auth_key' => '80VkUHANv_YFQJInBIWpPnTGklooy2F6',
            'password_hash' => '$2y$13$6gk0eI//HR4NmnUpSTPZXOXocLlW6AKiczrhNyHZ4ZtWoSidv9nYu',
            'email' => 'admin@coffeeplace.com',
            'status' => '10',
            'created_at' => '1540033114',
            'updated_at' => '1540033114',
        ]);
    }

    private function insertCoffee()
    {
        $this->insert('coffee', [
            'name' => 'Espresso',
            'intensity' => 'high',
            'price' => '1.6',
            'stock' => '21',
            'created_at' => '1540033115',
            'updated_at' => '1540033115',
        ]);

        $this->insert('coffee', [
            'name' => 'Cappuccino',
            'intensity' => 'medium',
            'price' => '3.25',
            'stock' => '16',
            'created_at' => '1540033116',
            'updated_at' => '1540033116',
        ]);

        $this->insert('coffee', [
            'name' => 'Latte',
            'intensity' => 'low',
            'price' => '3.10',
            'stock' => '20',
            'created_at' => '1540033117',
            'updated_at' => '1540033117',
        ]);
    }
}
