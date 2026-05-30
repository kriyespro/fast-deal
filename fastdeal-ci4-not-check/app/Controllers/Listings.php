<?php

namespace App\Controllers;

class Listings extends BaseController
{
    public function index()
    {
        $propertyModel = new \App\Models\PropertyModel();
        $query = $propertyModel->select('properties.*, agents.name as agent_name, agents.photo as agent_photo')
                               ->join('agents', 'agents.id = properties.agent_id', 'left')
                               ->where('properties.status', 'available');

        // Apply filters
        if ($search = $this->request->getGet('search')) {
            $query->groupStart()
                  ->like('title', $search)
                  ->orLike('city', $search)
                  ->groupEnd();
        }
        if ($city = $this->request->getGet('city')) {
            $query->like('city', $city);
        }
        if ($type = $this->request->getGet('type')) {
            $query->where('property_type', $type);
        }
        if ($listType = $this->request->getGet('listing_type')) {
            $query->where('listing_type', $listType);
        }
        if ($min = $this->request->getGet('min_price')) {
            $query->where('price >=', (float)$min);
        }
        if ($max = $this->request->getGet('max_price')) {
            $query->where('price <=', (float)$max);
        }
        if ($beds = $this->request->getGet('beds')) {
            $query->where('bedrooms >=', (int)$beds);
        }
        if ($baths = $this->request->getGet('baths')) {
            $query->where('bathrooms >=', (int)$baths);
        }

        $data['properties'] = $query->orderBy('id', 'DESC')->paginate(12);
        $data['pager'] = $propertyModel->pager;
        $data['total'] = $data['pager']->getTotal();
        $data['filters'] = $this->request->getGet();

        return view('listings/index', $data);
    }

    public function detail($slug = null)
    {
        $propertyModel = new \App\Models\PropertyModel();
        
        if (is_numeric($slug)) {
            $property = $propertyModel->find($slug);
        } else {
            $property = $propertyModel->where('slug', $slug)->first();
        }

        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $similarProperties = $propertyModel->where('status', 'available')
            ->where('id !=', $property['id'])
            ->where('property_type', $property['property_type'])
            ->orderBy('id', 'DESC')
            ->limit(3)
            ->find();
            
        if (count($similarProperties) < 3) {
            $existingIds = array_column($similarProperties, 'id');
            $existingIds[] = $property['id'];
            
            $moreProps = $propertyModel->where('status', 'available')
                ->whereNotIn('id', $existingIds)
                ->orderBy('id', 'DESC')
                ->limit(3 - count($similarProperties))
                ->find();
                
            $similarProperties = array_merge($similarProperties, $moreProps);
        }

        $data['property'] = $property;
        $data['similarProperties'] = $similarProperties;
        
        $agentModel = new \App\Models\AgentModel();
        $data['agent'] = $agentModel->find($property['agent_id']);
        
        return view('listings/detail', $data);
    }
}
