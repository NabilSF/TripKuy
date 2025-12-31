<?php
namespace App\Controller;

use App\Entity\Pembayaran;
use App\Entity\Reservasi;
use App\Entity\TipeKamar;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    #[Route("/checkout/{id}", name: "app_checkout")]
    public function index(TipeKamar $kamar, Request $request): Response
    {
        // Validasi stok dari entitas TipeKamar
        if ($kamar->getTotalKamar() <= 0) {
            $this->addFlash(
                "error",
                "Maaf, tipe kamar ini sudah habis terjual.",
            );
            return $this->redirectToRoute("hotel_detail", [
                "id" => $kamar->getHotel()->getId(),
            ]);
        }

        $checkin = new \DateTime($request->query->get("checkin", "now"));
        $checkout = new \DateTime($request->query->get("checkout", "+1 day"));
        $durasi = $checkin->diff($checkout)->days ?: 1;

        // Hitung total menggunakan getHarga() dari entitas TipeKamar
        $totalHarga = $kamar->getHarga() * $durasi;

        return $this->render("reservasi/checkout.html.twig", [
            "kamar" => $kamar,
            "checkin" => $checkin,
            "checkout" => $checkout,
            "durasi" => $durasi,
            "totalHarga" => $totalHarga,
        ]);
    }
    #[
        Route(
            "/payment/process/{id}",
            name: "app_payment_process",
            methods: ["POST"],
        ),
    ]
    public function process(
        TipeKamar $kamar,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $user = $this->getUser();

        // 1. Tangkap data tanggal dari form
        $tglCheckIn = new \DateTime($request->request->get("checkin"));
        $tglCheckOut = new \DateTime($request->request->get("checkout"));

        // 2. Hitung selisih malam (durasi)
        $interval = $tglCheckIn->diff($tglCheckOut);
        $totalMalam = $interval->days;

        // Jika check-in dan out di hari yang sama, minimal dihitung 1 malam
        if ($totalMalam < 1) {
            $totalMalam = 1;
        }

        // 3. Buat Data Pembayaran
        $pembayaran = new Pembayaran();
        $pembayaran->setTotalHarga((int) $request->request->get("total"));
        $pembayaran->setTipePembayaran($request->request->get("metode"));

        // 4. Buat Data Reservasi
        $reservasi = new Reservasi();
        $reservasi->setUser($user); //
        $reservasi->setKamar($kamar); //
        $reservasi->setTanggalCheckIn($tglCheckIn);
        $reservasi->setTanggalCheckOut($tglCheckOut);
        $reservasi->setPembayaran($pembayaran);
        $reservasi->setTotalMalam($totalMalam);
        $reservasi->setTanggalReservasi(new DateTime());
        $reservasi->setJumlahKamar($totalMalam);

        // 5. Update Stok Kamar
        $kamar->setTotalKamar($kamar->getTotalKamar() - 1);

        $em->persist($pembayaran);
        $em->persist($reservasi);
        $em->flush();

        $this->addFlash("success", "Reservasi berhasil disimpan!");
        return $this->redirectToRoute("app_dashboard");
    }
}
