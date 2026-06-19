<?php

namespace App\Controllers;

class HomeController extends BaseController {
    
    public function index(): void {
        $this->render('home', [
            'title' => 'Team Incubation | Empowering Through Education & Care',
            'active' => 'home'
        ]);
    }

    public function about(): void {
        $this->render('about', [
            'title' => 'About Us | Our Mission & Journey',
            'active' => 'about'
        ]);
    }

    public function contact(): void {
        $this->render('contact', [
            'title' => 'Contact Us | Reach out to Team Incubation',
            'active' => 'contact'
        ]);
    }
}
