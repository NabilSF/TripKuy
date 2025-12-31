<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route("/dashboard", name: "app_dashboard")]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render("dashboard/admin.html.twig", [
                "controller_name" => "DashboardController",
                "user" => $user,
            ]);
        }

        return $this->render("dashboard/user.html.twig", [
            "controller_name" => "DashboardController",
            "user" => $user,
        ]);
    }
}
