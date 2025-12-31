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
    /**
     * Route utama /dashboard sebagai pintu masuk.
     * User akan diarahkan sesuai Role mereka.
     */
    #[Route("/dashboard", name: "app_dashboard")]
    #[IsGranted("ROLE_USER")]
    public function index(): Response
    {
        $user = $this->getUser();

        // Jika user adalah ADMIN, arahkan ke dashboard admin
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute("admin_dashboard");
        }

        // Jika user biasa, render halaman user
        return $this->render("dashboard/user.html.twig", [
            "controller_name" => "DashboardController",
            "user" => $user,
        ]);
    }

    /**
     * Dashboard khusus Admin.
     * Hanya bisa diakses oleh user dengan ROLE_ADMIN.
     */
    #[Route("/admin/dashboard", name: "admin_dashboard")]
    #[IsGranted("ROLE_ADMIN")]
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

    /**
     * Aksi Approval Pembatalan.
     * Dikunci ketat dengan ROLE_ADMIN dan Method POST.
     */
    #[Route("/admin/pembatalan/{id}/approve", name: "admin_pembatalan_approve", methods: ["POST"])]
    #[IsGranted("ROLE_ADMIN")]
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