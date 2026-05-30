<?php

namespace App\Controllers;

use App\Models\BlogPostModel;

class Blog extends BaseController
{
    public function index()
    {
        $blogModel = new BlogPostModel();
        
        $data['posts'] = $blogModel->where('status', 'published')
                                   ->orderBy('published_at', 'DESC')
                                   ->paginate(12);
        $data['pager'] = $blogModel->pager;

        return view('blog/index', $data);
    }

    public function detail($slug = null)
    {
        $blogModel = new BlogPostModel();
        
        // Find by slug first, or ID fallback
        if (is_numeric($slug)) {
            $post = $blogModel->find($slug);
        } else {
            $post = $blogModel->where('slug', $slug)->first();
        }

        if (!$post || $post['status'] !== 'published') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['post'] = $post;
        
        // Fetch recent posts for sidebar
        $data['recent_posts'] = $blogModel->where('status', 'published')
                                          ->where('id !=', $post['id'])
                                          ->orderBy('published_at', 'DESC')
                                          ->limit(3)
                                          ->find();

        return view('blog/detail', $data);
    }
}
