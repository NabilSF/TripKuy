<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PembatalanController extends AbstractController
{
    #[Route('/pembatalan', name: 'app_pembatalan')]
    public function index(): Response
    {
        return $this->render('pembatalan/index.html.twig', [
            'controller_name' => 'PembatalanController',
        ]);
    }
}
