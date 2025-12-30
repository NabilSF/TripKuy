<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route("/admin", name: "admin_dashboard")]
    public function index()
    {
        return $this->render("admin_page/index.html.twig", [
            "page_title" => "Dashboard Admin",
            "user" => [
                "name" => "Admin Hotel",
                "role" => "Super Administrator",
            ],
        ]);
    }
}
