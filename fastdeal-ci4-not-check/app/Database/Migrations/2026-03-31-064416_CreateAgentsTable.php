<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone'            => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'whatsapp'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'photo'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bio'              => ['type' => 'TEXT', 'null' => true],
            'experience_years' => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'specialization'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'languages'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'rating'           => ['type' => 'DECIMAL', 'constraint' => '3,1', 'default' => 5.0],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('agents');
    }

    public function down()
    {
        $this->forge->dropTable('agents');
    }
}
