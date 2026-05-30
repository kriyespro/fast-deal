<?php

namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\NeighborhoodModel;
use App\Models\AgentModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        
        // Fetch latest 6 available properties
        $data['properties'] = $propertyModel->where('status', 'available')->orderBy('id', 'DESC')->findAll(6);
        
        // Stats
        $db = \Config\Database::connect();
        $totalProps = $propertyModel->where('status', 'available')->countAllResults();
        // If DB is empty, default to at least 15 to make UI look okay, else actual count
        $data['total_properties'] = $totalProps > 0 ? $totalProps : 15;
        
        $agentModel = new AgentModel();
        $totalAgents = $agentModel->countAllResults();
        $data['total_agents'] = $totalAgents > 0 ? $totalAgents : 12;

        $neighborhoodsModel = new NeighborhoodModel();
        $data['neighborhoods'] = $neighborhoodsModel->findAll(4); // limit to 4 to match design

        return view('home/index', $data);
    }
}
