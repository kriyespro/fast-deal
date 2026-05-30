<?php

namespace App\Models;

use CodeIgniter\Model;

class AgentModel extends Model
{
    protected $table            = 'agents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'name', 'email', 'phone', 'whatsapp', 
        'photo', 'bio', 'experience_years', 'specialization', 
        'languages', 'rating'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
