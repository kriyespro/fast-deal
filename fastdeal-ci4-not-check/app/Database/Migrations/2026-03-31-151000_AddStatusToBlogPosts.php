<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToBlogPosts extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'draft',
                'after'      => 'category'
            ],
        ];
        $this->forge->addColumn('blog_posts', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('blog_posts', 'status');
    }
}
