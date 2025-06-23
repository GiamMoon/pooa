<?php
require_once "../app/core/SecureController.php";
class HomeController extends SecureController{
    public function index() {
        //$this->view('home');
        $this->view('home', [
            'title' => 'Panel Principal',
            'activePage' => 'home',
            'openMenu' => 'home',
            'styles' => [
                'assets/vendor/libs/apex-charts/apex-charts.css',
                'assets/..'
            ],
            'vendors' => ['assets/vendor/libs/apex-charts/apexcharts.js'],
            'scripts' => ['assets/js/dashboards-analytics.js','/public/assets/js/app-ecommerce-product-list.js']
        ]);
    }
}