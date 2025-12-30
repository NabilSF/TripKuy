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
    #[
        Route(
            "/reservasi/{id}",
            name: "reservasi_form",
            methods: ["GET", "POST"],
        ),
    ]
    public function reservasi(
        Hotel $hotel,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        // 1. Ambil Parameter Tanggal dari URL (GET) atau Default
        $checkin = $request->query->get("checkin", date("Y-m-d"));
        $checkout = $request->query->get(
            "checkout",
            date("Y-m-d", strtotime("+1 day")),
        );

        // 2. Kalkulasi Durasi Menginap (Nights)
        try {
            $checkinDate = new \DateTime($checkin);
            $checkoutDate = new \DateTime($checkout);
            $interval = $checkinDate->diff($checkoutDate);
            $nights = max(1, $interval->days);
        } catch (\Exception $e) {
            $nights = 1;
            $checkinDate = new \DateTime();
            $checkoutDate = new \DateTime()->modify("+1 day");
        }

        // 3. Ambil Semua Tipe Kamar untuk Hotel Ini
        $kamarList = $em
            ->getRepository(TipeKamar::class)
            ->findBy(["hotel" => $hotel], ["harga" => "ASC"]);

        if (!$kamarList) {
            throw $this->createNotFoundException(
                "Hotel ini belum memiliki tipe kamar yang tersedia.",
            );
        }

        // 4. Tentukan Kamar yang Terpilih (Default: Kamar Pertama)
        $selectedRoomId =
            $request->request->get("tipe_kamar") ??
            $request->query->get("room_id");
        $selectedRoom = $kamarList[0]; // Default

        if ($selectedRoomId) {
            foreach ($kamarList as $kamar) {
                if ($kamar->getId() == $selectedRoomId) {
                    $selectedRoom = $kamar;
                    break;
                }
            }
        }

        // 5. Handle Form Submit (POST)
        if ($request->isMethod("POST")) {
            $jumlahKamar = (int) $request->request->get("jumlah_kamar", 1);
            $roomId = $request->request->get("tipe_kamar");

            $room = $em->getRepository(TipeKamar::class)->find($roomId);

            if (!$room) {
                $this->addFlash("error", "Tipe kamar tidak valid.");
            } else {
                // Kalkulasi Total Harga
                $totalHarga = $room->getHarga() * $nights * $jumlahKamar;

                // A. Simpan Data Pembayaran
                $pembayaran = new Pembayaran();
                $pembayaran->setTotalHarga($totalHarga);
                $pembayaran->setTipePembayaran("Transfer Bank"); // Atau sesuaikan
                $pembayaran->setTipePembayaran("Belum Dibayar");
                $em->persist($pembayaran);

                // B. Simpan Data Reservasi
                $reservasi = new Reservasi();
                $reservasi->setUser($this->getUser()); // Pastikan user login
                $reservasi->setKamar($room);
                $reservasi->setPembayaran($pembayaran);
                $reservasi->setTanggalCheckIn($checkinDate);
                $reservasi->setTanggalCheckOut($checkoutDate);
                $reservasi->setJumlahKamar($jumlahKamar);
                $reservasi->setTotalMalam($nights);
                $reservasi->setTanggalReservasi(new DateTime()); // Tambahkan ini
                $em->persist($reservasi);
                $em->flush();

                $this->addFlash(
                    "success",
                    "Reservasi berhasil dibuat! Nomor Invoice: #" .
                        str_pad($reservasi->getId(), 6, "0", STR_PAD_LEFT),
                );

                // Redirect ke halaman sukses atau ke form lagi
                return $this->redirectToRoute("reservasi_form", [
                    "id" => $hotel->getId(),
                ]);
            }
        }

        // 6. Render ke Twig
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
