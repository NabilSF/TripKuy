<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservasiController extends AbstractController
{
    #[Route('/reservasi', name: 'app_reservasi')]
    public function index(): Response
    {
        return $this->render('reservasi/index.html.twig', [
            'controller_name' => 'ReservasiController',
        ]);
    }
}
