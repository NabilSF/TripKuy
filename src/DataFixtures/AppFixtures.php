<?php

namespace App\DataFixtures;

use App\Entity\GambarHotel;
use App\Entity\GambarKamar;
use App\Entity\Hotel;
use App\Entity\TipeKamar;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        /**
         * =========================
         * BUAT OWNER
         * =========================
         */
        $owners = [];

        for ($i = 1; $i <= 5; $i++) {
            $owner = new User();
            $owner->setEmail("owner{$i}@hotel.test");
            $owner->setNama("owner-{$i}");
            $owner->setNoTelepon(rand(10000000, 99999999));
            $owner->setRoles(["ROLE_OWNER"]);
            $owner->setPassword(
                $this->passwordHasher->hashPassword($owner, "password"),
            );

            $manager->persist($owner);
            $owners[] = $owner;
        }

        /**
         * =========================
         * DATA HOTEL
         * =========================
         */
        $hotels = [
            [
                "nama" => "Grand Hyatt",
                "lokasi" => "Jakarta",
                "email" => "jakarta.grand@hyatt.com",
                "deskripsi" =>
                    "Ikon kemewahan kontemporer di jantung Jakarta yang menawarkan akses eksklusif ke pusat perbelanjaan kelas atas dan pemandangan Bundaran HI yang memukau.",
            ],
            [
                "nama" => "The Ritz-Carlton",
                "lokasi" => "Bali",
                "email" => "rc.balisz.reserves@ritzcarlton.com",
                "deskripsi" =>
                    "Resor tepi pantai yang memadukan keanggunan modern dengan tradisi spiritual Bali.",
            ],
            [
                "nama" => "Padma Resort",
                "lokasi" => "Bandung",
                "email" => "reservation@padmaresort.com",
                "deskripsi" =>
                    "Pelarian sempurna dengan udara pegunungan dan kolam renang ikonik.",
            ],
            [
                "nama" => "Hotel Indonesia Kempinski",
                "lokasi" => "Jakarta",
                "email" => "info.jakarta@kempinski.com",
                "deskripsi" =>
                    "Hotel bersejarah dengan standar kemewahan Eropa.",
            ],
            [
                "nama" => "Amanjiwo",
                "lokasi" => "Magelang",
                "email" => "amanjiwo@aman.com",
                "deskripsi" =>
                    "Resor eksklusif dengan ketenangan spiritual Borobudur.",
            ],
        ];

        foreach ($hotels as $index => $data) {
            $hotel = new Hotel();
            $hotel->setNamaHotel($data["nama"]);
            $hotel->setAlamat($data["lokasi"]);
            $hotel->setEmail($data["email"]);
            $hotel->setKontak("021-" . rand(111, 999) . rand(1000, 9999));
            $hotel->setDeskripsi($data["deskripsi"]);

            /**
             * SET OWNER (acak)
             */
            $hotel->setOwner($owners[array_rand($owners)]);

            /**
             * GAMBAR HOTEL
             */
            $hImage = new GambarHotel();
            $hImage->setFileName("hotel_" . ($index + 1) . ".jpg");
            $hImage->setHotel($hotel);
            $manager->persist($hImage);

            /**
             * TIPE KAMAR
             */
            $roomTypes = [
                [
                    "name" => "Superior Room",
                    "base" => 700000,
                    "filename" => "kamar_1.jpg",
                ],
                [
                    "name" => "Deluxe Suite",
                    "base" => 1200000,
                    "filename" => "kamar_2.jpg",
                ],
                [
                    "name" => "Presidential Suite",
                    "base" => 4500000,
                    "filename" => "kamar_3.jpg",
                ],
            ];

            foreach ($roomTypes as $roomData) {
                $tipe = new TipeKamar();
                $tipe->setNamaKamar($roomData["name"]);
                $tipe->setDeskripsi(
                    "Fasilitas premium dengan kenyamanan maksimal.",
                );
                $tipe->setKapasitasOrang(2);
                $tipe->setTotalKamar(rand(5, 15));
                $tipe->setHarga($roomData["base"] + rand(0, 10) * 50000);
                $tipe->setHotel($hotel);

                $tkImage = new GambarKamar();
                $tkImage->setFileName($roomData["filename"]);
                $tkImage->setTipeKamar($tipe);

                $manager->persist($tkImage);
                $manager->persist($tipe);
            }

            $manager->persist($hotel);
        }

        $manager->flush();
    }
}
