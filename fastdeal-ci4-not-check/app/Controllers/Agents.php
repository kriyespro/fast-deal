<?php

namespace App\Controllers;

use App\Models\AgentModel;
use App\Models\PropertyModel;

class Agents extends BaseController
{
    public function index()
    {
        $agentModel = new AgentModel();
        $data['agents'] = $agentModel->orderBy('name', 'ASC')->findAll();
        
        return view('agents/index', $data);
    }

    public function detail($id = null)
    {
        $agentModel = new AgentModel();
        $agent = $agentModel->find($id);

        if (!$agent) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $propertyModel = new PropertyModel();
        $properties = $propertyModel->where('agent_id', $id)->where('status', 'available')->orderBy('id', 'DESC')->findAll();

        $data = [
            'agent' => $agent,
            'properties' => $properties
        ];

        return view('agents/detail', $data);
    }
}
