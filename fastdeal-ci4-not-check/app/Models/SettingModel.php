<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['setting_key', 'value'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'updated_at'; // In migrations we didn't add created_at
    protected $updatedField  = 'updated_at';
    
    // Convenience method to retrieve all settings as a key-value array
    public function getAllAsMap()
    {
        $rows = $this->findAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['setting_key']] = $row['value'];
        }
        return $map;
    }

    // Convenience method to get a specific setting
    public function getSetting($key, $default = null)
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row['value'] : $default;
    }
}
