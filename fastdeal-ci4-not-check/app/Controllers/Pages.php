<?php

namespace App\Controllers;

use App\Models\LeadModel;

class Pages extends BaseController
{
    public function about()
    {
        return view('pages/about');
    }

    public function contact()
    {
        return view('pages/contact');
    }

    public function contactSubmit()
    {
        $rules = [
            'name' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'message' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error_messages', $this->validator->getErrors());
        }

        $leadModel = new LeadModel();
        $leadModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'message' => $this->request->getPost('message'),
            'property_id' => null,
            'status' => 'new'
        ]);

        return redirect()->back()->with('contact_success', 'Thank you! We will contact you shortly.');
    }

    public function newsletterSubscribe()
    {
        return redirect()->back()->with('contact_success', 'Subscribed to newsletter.');
    }
}
