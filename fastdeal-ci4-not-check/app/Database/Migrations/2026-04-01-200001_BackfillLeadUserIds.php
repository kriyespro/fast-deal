<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackfillLeadUserIds extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('user_id', 'leads')) {
            return;
        }

        $db    = $this->db;
        $users = $db->table('users')->get()->getResultArray();
        $map   = [];
        foreach ($users as $u) {
            $map[strtolower(trim((string) ($u['email'] ?? '')))] = (int) $u['id'];
        }

        $leads = $db->table('leads')->get()->getResultArray();
        foreach ($leads as $lead) {
            if (! empty($lead['user_id'])) {
                continue;
            }
            $key = strtolower(trim((string) ($lead['email'] ?? '')));
            if ($key !== '' && isset($map[$key])) {
                $db->table('leads')->where('id', $lead['id'])->update(['user_id' => $map[$key]]);
            }
        }
    }

    public function down()
    {
        // no-op
    }
}
