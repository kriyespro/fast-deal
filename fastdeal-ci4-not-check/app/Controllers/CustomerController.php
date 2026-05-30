<?php

namespace App\Controllers;

use App\Models\LeadModel;
use App\Models\PropertyModel;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $userEmail = strtolower(trim((string) $session->get('email')));
        $userId    = (int) $session->get('id');

        $leadModel = new LeadModel();
        // Match by logged-in user id (new rows) or normalized email (legacy rows)
        $data['leads'] = $leadModel->groupStart()
            ->where('user_id', $userId)
            ->orWhere('LOWER(TRIM(email))', $userEmail)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $propertyModel = new PropertyModel();
        foreach ($data['leads'] as $i => $lead) {
            if (!empty($lead['property_id'])) {
                $prop = $propertyModel->find((int) $lead['property_id']);
                $data['leads'][$i]['property_title'] = $prop['title'] ?? ('Property #' . $lead['property_id']);
            } else {
                $data['leads'][$i]['property_title'] = null;
            }
        }

        $userModel = new UserModel();
        $data['user'] = $userModel->find($session->get('id'));

        helper('image');
        $available = $propertyModel->where('status', 'available')->orderBy('id', 'DESC')->findAll();
        $data['propertiesForFavoritesJson'] = json_encode(
            array_map(static function (array $p): array {
                return [
                    'id'           => (int) $p['id'],
                    'title'        => $p['title'],
                    'price'        => (float) $p['price'],
                    'city'         => $p['city'] ?? '',
                    'address'      => $p['address'] ?? '',
                    'listing_type' => $p['listing_type'] ?? 'sale',
                    'bedrooms'     => $p['bedrooms'] ?? 0,
                    'bathrooms'    => $p['bathrooms'] ?? 0,
                    'image'        => image_url($p['main_image'] ?? ''),
                    'url'          => base_url('listings/' . $p['id']),
                ];
            }, $available),
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
        );

        return view('customer/dashboard', $data);
    }
    
    public function updateProfile()
    {
        $session = session();
        $userId = $session->get('id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]"
        ];
        
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];
        
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $data);
        
        // Update session
        $session->set([
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
