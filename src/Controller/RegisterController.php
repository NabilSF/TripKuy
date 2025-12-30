<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route("/register", name: "app_register", methods: ["GET"])]
    public function index(): Response
    {
        return $this->render("security/register.html.twig", [
            "controller_name" => "RegisterController",
        ]);
    }

    #[Route("/register", methods: ["POST"])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $email = $request->request->get("email");
        $nama = $request->request->get("fullname");
        $password = $request->request->get("password");
        $no_telepon = $request->request->get("phone");

        $error = null;

        if (!$email || !$nama || !$password || !$no_telepon) {
            $error = "field tidak boleh kosong!";
        } else {
            $user = new User();
            $user->setEmail($email);
            $user->setNama($nama);
            $user->setPassword($password);
            $user->setNoTelepon($no_telepon);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render("security/register.html.twig", [
            "email" => $email,
            "nama" => $nama,
            "password" => $password,
            "no_telepon" => $no_telepon,
            "error" => $error,
        ]);
    }
}
