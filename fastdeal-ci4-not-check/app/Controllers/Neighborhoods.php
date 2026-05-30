<?php

namespace App\Controllers;

class Neighborhoods extends BaseController
{
    public function index()
    {
        $neighborhoodModel = new \App\Models\NeighborhoodModel();
        $db = \Config\Database::connect();

        $data['neighborhoods'] = $neighborhoodModel->orderBy('created_at', 'DESC')->findAll();

        $cityCounts = [];
        $rows = $db->table('properties')
            ->select('city, COUNT(*) as total')
            ->where('status', 'available')
            ->groupBy('city')
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $city = (string) ($row['city'] ?? '');
            if ($city !== '') {
                $cityCounts[strtolower($city)] = (int) $row['total'];
            }
        }

        $data['cityCounts'] = $cityCounts;
        $data['totalNeighborhoods'] = count($data['neighborhoods']);

        return view('neighborhoods/index', $data);
    }
}
