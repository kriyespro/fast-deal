<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table = 'properties';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'title',
        'slug',
        'description',
        'price',
        'listing_type',
        'property_type',
        'status',
        'address',
        'city',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'features',
        'main_image',
        'gallery_images',
        'agent_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'price' => 'required|numeric',
        'listing_type' => 'required|in_list[sale,rent]',
        'property_type' => 'required',
        'status' => 'required|in_list[available,pending,sold,rented]',
    ];

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['title'])) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['data']['title'])));
            $data['data']['slug'] = $slug . '-' . time(); // guarantee uniqueness
        }
        return $data;
    }
}
