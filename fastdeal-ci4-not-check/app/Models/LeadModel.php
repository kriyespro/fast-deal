<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadModel extends Model
{
    protected $table = 'leads';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'email',
        'user_id',
        'phone',
        'property_id',
        'message',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]',
        'email' => 'required|valid_email',
        'message' => 'required|min_length[5]',
    ];
}
