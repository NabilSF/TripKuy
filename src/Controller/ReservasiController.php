<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\TipeKamar;
use App\Entity\Reservasi;
use App\Entity\Pembayaran;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservasiController extends AbstractController
{
    /**
     * LANGKAH 1: Form Pemilihan Kamar & Tanggal
     */
    #[Route("/reservasi/{id}", name: "reservasi_form", methods: ["GET"])]
    public function reservasiForm(
        Hotel $hotel,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $checkin = $request->query->get("checkin", date("Y-m-d"));
        $checkout = $request->query->get(
            "checkout",
            date("Y-m-d", strtotime("+1 day")),
        );

        // Ambil list kamar
        $kamarList = $em
            ->getRepository(TipeKamar::class)
            ->findBy(["hotel" => $hotel], ["harga" => "ASC"]);

        if (!$kamarList) {
            throw $this->createNotFoundException(
                "Hotel ini belum memiliki tipe kamar.",
            );
        }

        // FIX: Tentukan selectedRoom agar tidak error di Twig
        $selectedRoomId = $request->query->get("room_id");
        $selectedRoom = $kamarList[0]; // Default kamar pertama

        if ($selectedRoomId) {
            foreach ($kamarList as $kamar) {
                if ($kamar->getId() == $selectedRoomId) {
                    $selectedRoom = $kamar;
                    break;
                }
            }
        }

        // Hitung nights untuk tampilan di form (opsional)
        $nights = max(
            1,
            new \DateTime($checkin)->diff(new \DateTime($checkout))->days,
        );

        return $this->render("reservasi/index.html.twig", [
            "hotel" => $hotel,
            "kamarList" => $kamarList,
            "selectedRoom" => $selectedRoom, // Sekarang variabel ini dikirim ke Twig
            "checkin" => $checkin,
            "checkout" => $checkout,
            "nights" => $nights,
        ]);
    }

    /**
     * LANGKAH 2: Halaman Checkout (Ringkasan Pesanan)
     */
    #[
        Route(
            "/reservasi/{id}/checkout",
            name: "reservasi_checkout",
            methods: ["POST"],
        ),
    ]
    #[
        Route(
            "/reservasi/{id}/checkout",
            name: "reservasi_checkout",
            methods: ["POST"],
        ),
    ]
    public function checkout(
        Hotel $hotel,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $roomId = $request->request->get("tipe_kamar");
        $room = $em->getRepository(TipeKamar::class)->find($roomId);

        $checkin = $request->request->get("checkin");
        $checkout = $request->request->get("checkout");
        $jumlahKamar = (int) $request->request->get("jumlah_kamar", 1);

        $nights = max(
            1,
            new \DateTime($checkin)->diff(new \DateTime($checkout))->days,
        );
        $totalHarga = $room->getHarga() * $nights * $jumlahKamar;

        return $this->render("reservasi/checkout.html.twig", [
            "hotel" => $hotel,
            "room" => $room,
            "checkin" => $checkin,
            "checkout" => $checkout,
            "nights" => $nights,
            "jumlahKamar" => $jumlahKamar,
            "totalHarga" => $totalHarga,
        ]);
    }

    #[
        Route(
            "/reservasi/{id}/confirm",
            name: "reservasi_confirm",
            methods: ["POST"],
        ),
    ]
    public function confirm(
        Hotel $hotel,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $room = $em
            ->getRepository(TipeKamar::class)
            ->find($request->request->get("room_id"));
        $metode = $request->request->get("metode_pembayaran"); // Ambil dari radio button

        // 1. Simpan Pembayaran dengan Status Pending
        $pembayaran = new Pembayaran();
        $pembayaran->setTotalHarga((int) $request->request->get("total_harga"));
        // Simpan metode + status (Contoh: "Transfer Bank (Belum Bayar)")
        $pembayaran->setTipePembayaran($metode . " (Belum Bayar)");
        $em->persist($pembayaran);

        // 2. Simpan Reservasi
        $reservasi = new Reservasi();
        $reservasi->setUser($this->getUser());
        $reservasi->setKamar($room);
        $reservasi->setPembayaran($pembayaran);
        $reservasi->setTanggalCheckIn(
            new \DateTime($request->request->get("checkin")),
        );
        $reservasi->setTanggalCheckOut(
            new \DateTime($request->request->get("checkout")),
        );
        $reservasi->setJumlahKamar(
            (int) $request->request->get("jumlah_kamar"),
        );
        $reservasi->setTotalMalam((int) $request->request->get("nights"));
        $reservasi->setTanggalReservasi(new \DateTime());

        $em->persist($reservasi);
        $em->flush();

        $this->addFlash(
            "success",
            "Reservasi berhasil dibuat! Silahkan selesaikan pembayaran " .
                $metode,
        );
        return $this->redirectToRoute("app_dashboard"); // Arahkan ke history pesanan
    }
}
