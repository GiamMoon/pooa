<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/SecureController.php';


class DashboardController extends SecureController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $data = [
            'title'      => 'Dashboard BI',
            'activePage' => 'dashboard',
            'openMenu'   => ''
        ];

        $this->view('dashboard/index', $data);
    }
}
