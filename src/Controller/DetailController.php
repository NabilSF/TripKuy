<?php

namespace App\Controller;

use App\Entity\GambarKamar;
use App\Entity\Hotel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailController extends AbstractController
{
    #[Route("/hotel/{id}", name: "hotel_detail")]
    public function show(Hotel $hotel, Request $request): Response
    {
        // Mengambil koleksi tipe kamar dari entity Hotel
        $tipeKamars = $hotel->getTipeKamars();

        // Tangkap data tanggal dari query string atau set default
        $checkin = $request->query->get("checkin", date("Y-m-d"));
        $checkout = $request->query->get(
            "checkout",
            date("Y-m-d", strtotime("+1 day")),
        );

        return $this->render("hotel/detail.html.twig", [
            "hotel" => $hotel,
            "tipeKamars" => $tipeKamars,
            "checkin" => $checkin,
            "checkout" => $checkout,
        ]);
    }
}
