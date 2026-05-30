<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Errors extends BaseController
{
    public function show404()
    {
        $this->response->setStatusCode(404);
        return view('errors/custom_404');
    }
}
