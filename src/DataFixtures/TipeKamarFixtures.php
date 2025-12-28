<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\TipeKamar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TipeKamarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $kamers = [
            [1, 1, "Deluxe King Room", "Bundaran HI", 2, 10, 3500000],
            [2, 1, "Presidential Suite", "Suite terbaik", 4, 2, 15000000],
            [3, 2, "Deluxe Mountain View", "Pegunungan", 2, 20, 2200000],
            [4, 3, "Javanese Royal Suite", "Nuansa Jawa", 2, 5, 2800000],
            [5, 7, "Ocean Front Lagoon", "Laguna", 2, 15, 4200000],
            [6, 14, "One Bedroom Pool Villa", "Villa privat", 2, 8, 12000000],
        ];

        foreach ($kamers as $k) {
            $kamar = new TipeKamar();
            $kamar->setNamaKamar($k[2]);
            $kamar->setDeskripsi($k[3]);
            $kamar->setKapasitasOrang($k[4]);
            $kamar->setTotalKamar($k[5]);
            $kamar->setHarga($k[6]);
            $kamar->setHotel(
                $this->getReference("hotel-" . $k[1], Hotel::class),
            );

            $manager->persist($kamar);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [HotelFixtures::class];
    }
}
