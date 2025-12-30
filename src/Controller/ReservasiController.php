<?php
namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\TipeKamar;
use App\Entity\Reservasi;
use App\Entity\Pembayaran;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservasiController extends AbstractController
{
    #[Route("/reservasi", name: "reservasi_form")]
    public function reservasi(
        EntityManagerInterface $em,
        Request $request,
    ): Response {
        // ID statis untuk testing
        $hotel = $em->getRepository(Hotel::class)->find(1);

        if (!$hotel) {
            throw $this->createNotFoundException(
                "Hotel dengan ID 1 tidak ditemukan",
            );
        }

        $checkin = $request->query->get("checkin", date("Y-m-d"));
        $checkout = $request->query->get(
            "checkout",
            date("Y-m-d", strtotime("+1 day")),
        );

        $checkinDate = new \DateTime($checkin);
        $checkoutDate = new \DateTime($checkout);
        $nights = max(1, $checkinDate->diff($checkoutDate)->days);

        $kamarList = $em
            ->getRepository(TipeKamar::class)
            ->findBy(["hotel" => $hotel], ["harga" => "ASC"]);

        $selectedRoom = $kamarList[0] ?? null;

        return $this->render("reservasi/index.html.twig", [
            "hotel" => $hotel,
            "kamarList" => $kamarList,
            "selectedRoom" => $selectedRoom,
            "checkin" => $checkin,
            "checkout" => $checkout,
            "nights" => $nights,
        ]);
    }
}
