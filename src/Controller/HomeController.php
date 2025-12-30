<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route("/home", name: "app_home")]
    public function index(HotelRepository $hotelRepository): Response
    {
        $hotels = $hotelRepository->findHotelWithMinHargaKamar();
        return $this->render("hotel/index.html.twig", [
            "controller_name" => "HomeController",
            "hotels" => $hotels,
        ]);
    }
}
