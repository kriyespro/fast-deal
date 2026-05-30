<?php

namespace App\Controllers;

use App\Models\LeadModel;

class LeadController extends BaseController
{
    public function submit()
    {
        $leadModel = new LeadModel();

        $rules = [
            'name' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'message' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('lead_errors', $this->validator->getErrors());
        }

        $session = session();
        // Logged-in users: use account email so customer dashboard matches inquiries
        $email = $session->get('isLoggedIn')
            ? strtolower(trim((string) $session->get('email')))
            : strtolower(trim((string) $this->request->getPost('email')));

        $userId = $session->get('isLoggedIn') ? (int) $session->get('id') : 0;

        $leadModel->insert([
            'name'          => $this->request->getPost('name'),
            'email'         => $email,
            'user_id'       => $userId > 0 ? $userId : null,
            'phone'         => $this->request->getPost('phone'),
            'property_id'   => $this->request->getPost('property_id'),
            'message'       => $this->request->getPost('message'),
            'status'        => 'new',
        ]);

        return redirect()->back()->with('lead_success', 'Your message has been sent! An agent will contact you shortly.');
    }
}
