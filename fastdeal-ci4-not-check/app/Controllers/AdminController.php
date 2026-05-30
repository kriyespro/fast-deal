<?php

namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\LeadModel;
use App\Models\AgentModel;
use App\Models\NeighborhoodModel;
use App\Models\BlogPostModel;
use App\Models\SettingModel;

class AdminController extends BaseController
{
    protected $propertyModel;
    protected $leadModel;
    protected $agentModel;
    protected $neighborhoodModel;
    protected $blogPostModel;
    protected $settingModel;

    public function __construct()
    {
        $this->propertyModel = new PropertyModel();
        $this->leadModel = new LeadModel();
        $this->agentModel = new AgentModel();
        $this->neighborhoodModel = new NeighborhoodModel();
        $this->blogPostModel = new BlogPostModel();
        $this->settingModel = new SettingModel();
        helper('url');
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $db = \Config\Database::connect();

        $data['totalListings'] = $this->propertyModel->countAll();
        $data['activeListings'] = $this->propertyModel->where('status', 'available')->countAllResults();
        $data['totalLeads'] = $this->leadModel->countAll();
        $data['newLeads'] = $this->leadModel->where('status', 'new')->countAllResults();
        $data['recentLeads'] = $this->leadModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
        $data['recentListings'] = $this->propertyModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
        $data['totalUsers'] = $db->table('users')->countAll();

        return view('admin/dashboard', $data);
    }

    // ─── Property Listings ────────────────────────────────────────────────────
    public function listings()
    {
        $data['properties'] = $this->propertyModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/listings', $data);
    }

    public function createProperty()
    {
        return view('admin/property_form');
    }

    public function storeProperty()
    {
        $rules = [
            'title' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'property_type' => 'required',
            'listing_type' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle multiple images from images[] input
        $uploadedFiles = $this->request->getFiles();
        $images = isset($uploadedFiles['images']) ? (array) $uploadedFiles['images'] : [];
        $validImages = array_filter($images, fn($f) => $f->isValid() && !$f->hasMoved() && $f->getSize() > 0);

        if (empty($validImages)) {
            return redirect()->back()->withInput()->with('errors', ['images' => 'Please upload at least one property image.']);
        }

        $uploadDir = FCPATH . 'uploads/properties/';
        $mainImage = null;
        $galleryPaths = [];

        foreach (array_values($validImages) as $index => $img) {
            $ext = strtolower($img->getExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                continue;
            if ($img->getSize() > 5 * 1024 * 1024)
                continue;

            $newName = $img->getRandomName();
            $img->move($uploadDir, $newName);
            $path = 'uploads/properties/' . $newName;

            if ($mainImage === null) {
                $mainImage = $path;
            } else {
                $galleryPaths[] = $path;
            }
        }

        $features = $this->request->getPost('features') ? json_encode($this->request->getPost('features')) : null;

        $this->propertyModel->insert([
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'listing_type' => $this->request->getPost('listing_type'),
            'property_type' => $this->request->getPost('property_type'),
            'status' => $this->request->getPost('status') ?: 'available',
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'bedrooms' => (int) $this->request->getPost('bedrooms'),
            'bathrooms' => (int) $this->request->getPost('bathrooms'),
            'area_sqft' => (int) $this->request->getPost('area_sqft'),
            'features' => $features,
            'main_image' => $mainImage,
            'gallery_images' => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'agent_id' => session()->get('id'),
        ]);

        $total = count($validImages);
        return redirect()->to('/admin/listings')->with('success', "Property created with {$total} image(s)!");
    }

    public function editProperty($id)
    {
        $data['property'] = $this->propertyModel->find($id);
        if (!$data['property']) {
            return redirect()->to('/admin/listings')->with('error', 'Property not found.');
        }
        return view('admin/property_form', $data);
    }

    public function updateProperty($id)
    {
        $property = $this->propertyModel->find($id);
        if (!$property) {
            return redirect()->to('/admin/listings')->with('error', 'Property not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'property_type' => 'required',
            'listing_type' => 'required',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // ── Phase 1: Process deletions ───────────────────────────────
        $toDelete = $this->request->getPost('delete_images') ?? [];
        $toDelete = is_array($toDelete) ? $toDelete : [];

        // Build the current full image list [main, ...gallery]
        $allExisting = [];
        if (!empty($property['main_image'])) {
            $allExisting[] = $property['main_image'];
        }
        $gallery = json_decode($property['gallery_images'] ?? '[]', true) ?: [];
        foreach ($gallery as $g) {
            if (!empty($g))
                $allExisting[] = $g;
        }

        // Delete files from disk and remove from list
        foreach ($toDelete as $path) {
            $fullPath = FCPATH . ltrim($path, '/');
            if (file_exists($fullPath))
                @unlink($fullPath);
            $allExisting = array_filter($allExisting, fn($p) => $p !== $path);
        }
        $allExisting = array_values($allExisting); // re-index

        // ── Phase 2: Upload and append new images ────────────────────
        $uploadedFiles = $this->request->getFiles();
        $images = isset($uploadedFiles['images']) ? (array) $uploadedFiles['images'] : [];
        $validImages = array_filter($images, fn($f) => $f->isValid() && !$f->hasMoved() && $f->getSize() > 0);
        $uploadCount = 0;

        foreach (array_values($validImages) as $img) {
            $ext = strtolower($img->getExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                continue;
            if ($img->getSize() > 5 * 1024 * 1024)
                continue;

            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads/properties/', $newName);
            $allExisting[] = 'uploads/properties/' . $newName;
            $uploadCount++;
        }

        // ── Phase 3: Re-assign main_image and gallery_images ─────────
        $newMain = $allExisting[0] ?? null;
        $newGallery = array_slice($allExisting, 1);

        $this->propertyModel->update($id, [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'listing_type' => $this->request->getPost('listing_type'),
            'property_type' => $this->request->getPost('property_type'),
            'status' => $this->request->getPost('status') ?: 'available',
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'bedrooms' => (int) $this->request->getPost('bedrooms'),
            'bathrooms' => (int) $this->request->getPost('bathrooms'),
            'area_sqft' => (int) $this->request->getPost('area_sqft'),
            'features' => $this->request->getPost('features') ? json_encode($this->request->getPost('features')) : null,
            'main_image' => $newMain,
            'gallery_images' => !empty($newGallery) ? json_encode(array_values($newGallery)) : null,
        ]);

        $deletedCount = count($toDelete);
        $parts = [];
        if ($deletedCount > 0)
            $parts[] = "{$deletedCount} image(s) removed";
        if ($uploadCount > 0)
            $parts[] = "{$uploadCount} new image(s) added";
        $msg = !empty($parts) ? 'Property updated: ' . implode(', ', $parts) . '.' : 'Property updated successfully!';

        return redirect()->to('/admin/listings')->with('success', $msg);
    }



    public function deleteProperty($id)
    {
        $property = $this->propertyModel->find($id);
        if ($property && $property['main_image'] && file_exists(FCPATH . $property['main_image'])) {
            unlink(FCPATH . $property['main_image']);
        }
        $this->propertyModel->delete($id);
        return redirect()->to('/admin/listings')->with('success', 'Property deleted.');
    }

    // ─── Agents ───────────────────────────────────────────────────────────────
    public function agents()
    {
        $data['agents'] = $this->agentModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/agents', $data);
    }

    public function storeAgent()
    {
        $rules = [
            'name'  => 'required|min_length[2]',
            'email' => 'valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/agents')->with('errors', $this->validator->getErrors());
        }

        // Handle Photo upload
        $photo = $this->request->getFile('photo');
        $photoPath = null;
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/agents/', $newName);
            $photoPath = 'uploads/agents/' . $newName;
        }

        $this->agentModel->insert([
            'name'             => $this->request->getPost('name'),
            'email'            => $this->request->getPost('email'),
            'phone'            => $this->request->getPost('phone'),
            'whatsapp'         => $this->request->getPost('whatsapp'),
            'photo'            => $photoPath,
            'bio'              => $this->request->getPost('bio'),
            'experience_years' => $this->request->getPost('experience_years'),
            'specialization'   => $this->request->getPost('specialization'),
            'languages'        => $this->request->getPost('languages')
        ]);

        return redirect()->to('/admin/agents')->with('success', 'Agent created successfully!');
    }

    public function deleteAgent($id)
    {
        $agent = $this->agentModel->find($id);
        if ($agent && $agent['photo'] && file_exists(FCPATH . ltrim($agent['photo'], '/'))) {
            @unlink(FCPATH . ltrim($agent['photo'], '/'));
        }
        $this->agentModel->delete($id);
        return redirect()->to('/admin/agents')->with('success', 'Agent deleted.');
    }

    // ─── Leads ────────────────────────────────────────────────────────────────
    public function leads()
    {
        $data['leads'] = $this->leadModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/leads', $data);
    }

    public function markLead($id, $status)
    {
        $this->leadModel->update($id, ['status' => $status]);
        return redirect()->to('/admin/leads')->with('success', 'Lead status updated.');
    }

    public function deleteLead($id)
    {
        $this->leadModel->delete($id);
        return redirect()->to('/admin/leads')->with('success', 'Lead removed.');
    }

    // ─── Settings ─────────────────────────────────────────────────────────────
    public function settings()
    {
        $data['settings'] = $this->settingModel->getAllAsMap();
        return view('admin/settings', $data);
    }

    public function saveSettings()
    {
        $postData = $this->request->getPost();
        
        foreach ($postData as $key => $value) {
            // Check if key exists
            $existing = $this->settingModel->where('setting_key', $key)->first();
            if ($existing) {
                $this->settingModel->update($existing['id'], ['value' => $value]);
            } else {
                $this->settingModel->insert(['setting_key' => $key, 'value' => $value]);
            }
        }
        
        return redirect()->to('/admin/settings')->with('success', 'Settings saved successfully!');
    }

    // ─── Neighborhoods ────────────────────────────────────────────────────────
    public function neighborhoods()
    {
        $data['neighborhoods'] = $this->neighborhoodModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/neighborhoods', $data);
    }

    public function storeNeighborhood()
    {
        $this->neighborhoodModel->insert([
            'name'        => $this->request->getPost('name'),
            'city'        => $this->request->getPost('city'),
            'description' => $this->request->getPost('description'),
            'image_path'  => $this->request->getPost('image_path') // Optional: Handle file upload later
        ]);
        return redirect()->to('/admin/neighborhoods')->with('success', 'Neighborhood created!');
    }
    
    public function deleteNeighborhood($id)
    {
        $this->neighborhoodModel->delete($id);
        return redirect()->to('/admin/neighborhoods')->with('success', 'Neighborhood deleted.');
    }

    // ─── Blog Posts ───────────────────────────────────────────────────────────
    public function blog()
    {
        $data['posts'] = $this->blogPostModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/blog', $data);
    }
    
    public function createBlog()
    {
        return view('admin/blog_form');
    }

    public function storeBlog()
    {
        $title = $this->request->getPost('title');
        // generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

        $this->blogPostModel->insert([
            'title'          => $title,
            'slug'           => $slug,
            'category'       => $this->request->getPost('category'),
            'excerpt'        => $this->request->getPost('excerpt'),
            'content'        => $this->request->getPost('content'),
            'featured_image' => $this->request->getPost('featured_image') ?: null,
            'author_id'      => session()->get('id') ?: null,
            'status'         => 'published',
            'published_at'   => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/blog')->with('success', 'Blog post created!');
    }

    public function deleteBlog($id)
    {
        $this->blogPostModel->delete($id);
        return redirect()->to('/admin/blog')->with('success', 'Blog post deleted.');
    }
}
