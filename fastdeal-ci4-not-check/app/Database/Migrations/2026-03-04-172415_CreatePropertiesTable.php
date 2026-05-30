<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'listing_type' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'sale', // sale, rent
            ],
            'property_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'available', // available, pending, sold, rented
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'bedrooms' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'bathrooms' => [
                'type' => 'DECIMAL',
                'constraint' => '3,1',
                'null' => true,
            ],
            'area_sqft' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'features' => [
                'type' => 'TEXT', // JSON array
                'null' => true,
            ],
            'main_image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'agent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('agent_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('properties');
    }

    public function down()
    {
        $this->forge->dropTable('properties');
    }
}
