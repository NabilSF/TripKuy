<?php

namespace App\Controller;

use App\Repository\PembatalanRepository;
use App\Repository\ReservasiRepository;
use App\Repository\TipeKamarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashboardController extends AbstractController
{
    #[Route("/dashboard", name: "app_dashboard")]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute("admin_dashboard");
        }

        return $this->render("dashboard/user.html.twig", [
            "controller_name" => "DashboardController",
            "user" => $user,
        ]);
    }

    #[Route("/admin/dashboard", name: "admin_dashboard")]
    public function admin_dashboard(
        TipeKamarRepository $tipeKamarRepo,
        PembatalanRepository $pembatalanRepo,
        ReservasiRepository $reservasiRepo,
    ): Response {
        $allTipeKamar = $tipeKamarRepo->findAll();
        $allPembatalan = $pembatalanRepo->findAll();
        $allReservasi = $reservasiRepo->findAll();

        return $this->render("dashboard/admin.html.twig", [
            "all_tipe_kamar" => $allTipeKamar,
            "all_pembatalan" => $allPembatalan,
            "total_reservasi" => count($allReservasi),
        ]);
    }

    #[
        Route(
            "/admin/pembatalan/{id}/approve",
            name: "admin_pembatalan_approve",
            methods: ["POST"],
        ),
    ]
    public function approveCancellation(
        $id,
        PembatalanRepository $pembatalanRepo,
    ): Response {
        // Logika untuk memproses refund atau update catatanAdmin di Pembatalan.php
        // ... kode persistence database ...

        $this->addFlash("success", "Pembatalan berhasil diproses.");
        return $this->redirectToRoute("admin_dashboard");
    }
}
