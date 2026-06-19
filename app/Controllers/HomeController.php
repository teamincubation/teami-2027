<?php

namespace App\Controllers;

class HomeController extends BaseController {
    
    public function index(): void {
        $db = \App\Models\BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM banners WHERE active = 1 AND display_location = 'home_hero' ORDER BY display_order ASC");
        $banners = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('home', [
            'title' => 'Team Incubation | Empowering Through Education & Care',
            'active' => 'home',
            'banners' => $banners
        ]);
    }

    public function about(): void {
        $db = \App\Models\BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM legacy_milestones WHERE active = 1 ORDER BY year ASC, display_order ASC");
        $milestones = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('about', [
            'title' => 'About Us | Our Mission & Journey',
            'active' => 'about',
            'milestones' => $milestones
        ]);
    }

    public function contact(): void {
        $this->render('contact', [
            'title' => 'Contact Us | Reach out to Team Incubation',
            'active' => 'contact'
        ]);
    }
}
