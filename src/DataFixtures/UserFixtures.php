<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                "id" => "user-1",
                "nama" => "admin",
                "email" => "admin@admin",
                "password" =>
                    '$2y$10$DVfNw8X1B2/ywRHlYKvy6.cAdytowAwKgDW4Jwcl5lCF.nrlKwcv6',
                "roles" => ["ROLE_ADMIN"],
                "telp" => "08123123123",
            ],
            [
                "id" => "user-2",
                "nama" => "Edward Newgate",
                "email" => "edward@gmail.ac.id",
                "password" =>
                    '$2y$10$uznAdoti84/cvNRKgBbVbOIAJCvo.3Ec6yWPN8tGdwpeAwz0pjtZ6',
                "roles" => ["ROLE_ADMIN"],
                "telp" => "081122334455",
            ],
            [
                "id" => "user-3",
                "nama" => "Kira Yamato",
                "email" => "kira.yamato@gmail.com",
                "password" =>
                    '$2y$10$txGfGV7ob3mgD5LFwaqwOelvO1wjEmFXAehI3yhV9JPX1Ey6/CGo.',
                "roles" => ["ROLE_USER"],
                "telp" => "085700011122",
            ],
            [
                "id" => "user-4",
                "nama" => "Banager Links",
                "email" => "banager@gmail.com",
                "password" =>
                    '$2y$10$i3p4skSGt.oh66L/KiCjuO63b0DDGjIShKzvohx5EdsxfDwR/vaCe',
                "roles" => ["ROLE_USER"],
                "telp" => "081299988877",
            ],
            [
                "id" => "user-5",
                "nama" => "Andi Wijaya",
                "email" => "andi.wijaya@gmail.com",
                "password" =>
                    '$2y$10$zaJLT5bpJPJRBCxeOTlPoeT4eGMHFSgayv33KxjloLiBGfnXQFr2a',
                "roles" => ["ROLE_USER"],
                "telp" => "081344455566",
            ],
            [
                "id" => "user-6",
                "nama" => "Dewi Lestari",
                "email" => "dewi.lestari@gmail.com",
                "password" =>
                    '$2y$10$RD/rhyqlxkdQU82q/nWJouEhNjO9uoPSkOMOZ2oGfhciBFRyQzxaG',
                "roles" => ["ROLE_USER"],
                "telp" => "081911223344",
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setNama($data["nama"]);
            $user->setEmail($data["email"]);
            $user->setPassword($data["password"]); // HASH ASLI
            $user->setRoles($data["roles"]);
            $user->setNoTelepon($data["telp"]);

            $manager->persist($user);
            $this->addReference($data["id"], $user);
        }

        $manager->flush();
    }
}
