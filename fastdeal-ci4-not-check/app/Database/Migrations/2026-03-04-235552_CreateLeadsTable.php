<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeadsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'property_id' => ['type' => 'INTEGER', 'null' => true],
            'message' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'new'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('leads', true);
    }

    public function down()
    {
        $this->forge->dropTable('leads', true);
    }
}
