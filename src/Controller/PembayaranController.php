<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PembayaranController extends AbstractController
{
    #[Route('/pembayaran', name: 'app_pembayaran')]
    public function index(): Response
    {
        return $this->render('pembayaran/index.html.twig', [
            'controller_name' => 'PembayaranController',
        ]);
    }
}
