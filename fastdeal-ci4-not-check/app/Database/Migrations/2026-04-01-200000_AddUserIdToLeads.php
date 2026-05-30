<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToLeads extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('user_id', 'leads')) {
            return;
        }

        $this->forge->addColumn('leads', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('user_id', 'leads')) {
            $this->forge->dropColumn('leads', 'user_id');
        }
    }
}
