<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HotelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $hotels = [
            [
                1,
                "Hotel Indonesia Kempinski",
                "info.jakarta@kempinski.com",
                "Jakarta",
                "(021) 23583800",
                "Hotel bintang 5 bersejarah",
            ],
            [
                2,
                "The Gaia Hotel Bandung",
                "reservation@thegaiabandung.com",
                "Bandung",
                "(022) 20280780",
                "Resor pegunungan",
            ],
            [
                3,
                "Hotel Tentrem Yogyakarta",
                "info.jogja@hoteltentrem.com",
                "Yogyakarta",
                "(0274) 6415555",
                "Hotel mewah Jawa",
            ],
            [
                4,
                "Pullman Lombok Merujani",
                "all_reservation@pullman-lombok.com",
                "Lombok",
                "(0370) 7525100",
                "Resor pantai",
            ],
            [
                5,
                "CLARO Makassar",
                "info@claromakassar.com",
                "Makassar",
                "(0411) 833888",
                "Hotel bisnis",
            ],
            [
                6,
                "Padma Hotel Semarang",
                "reservation.semarang@padmahotels.com",
                "Semarang",
                "(024) 33000900",
                "Resor perbukitan",
            ],
            [
                7,
                "Conrad Bali",
                "conrad_bali@hilton.com",
                "Bali",
                "(0361) 778788",
                "Resor laguna",
            ],
            [
                8,
                "InterContinental Jakarta Pondok Indah",
                "reservation.jktpi@ihg.com",
                "Jakarta",
                "(021) 39507355",
                "Hotel premium",
            ],
            [
                9,
                "Grand Hyatt Jakarta",
                "jakarta.grand@hyatt.com",
                "Jakarta",
                "(021) 29921234",
                "Hotel bisnis",
            ],
            [
                10,
                "Shangri-La Jakarta",
                "slj@shangri-la.com",
                "Jakarta",
                "(021) 29229999",
                "Hotel klasik",
            ],
            [
                11,
                "JW Marriott Hotel Jakarta",
                "res.jkt@marriott.com",
                "Jakarta",
                "(021) 57988888",
                "Hotel Mega Kuningan",
            ],
            [
                12,
                "Mulia Senayan Hotel",
                "info@hotelmulia.com",
                "Jakarta",
                "(021) 5747777",
                "Hotel golf",
            ],
            [
                13,
                "The Westin Jakarta",
                "westin.jakarta@westin.com",
                "Jakarta",
                "(021) 27887788",
                "Hotel tertinggi",
            ],
            [
                14,
                "Alila Villas Uluwatu",
                "uluwatu@alilahotels.com",
                "Bali",
                "(0361) 8482166",
                "Villa tebing",
            ],
            [
                15,
                "Bulgari Resort Bali",
                "infobali@bulgarihotels.com",
                "Bali",
                "(0361) 8471000",
                "Resor eksklusif",
            ],
            [
                16,
                "Ayana Resort Bali",
                "reservation@ayanaresort.com",
                "Bali",
                "(0361) 702222",
                "Resor Rock Bar",
            ],
            [
                17,
                "Gumaya Tower Hotel",
                "info@gumayatowerhotel.com",
                "Semarang",
                "(024) 3551999",
                "Hotel tinggi",
            ],
            [
                18,
                "Adhiwangsa Hotel Solo",
                "info@adhiwangsasolo.id",
                "Solo",
                "(0271) 7464999",
                "Hotel istana",
            ],
            [
                19,
                "Four Seasons Jakarta",
                "contact@fourseasons.com",
                "Jakarta",
                "(021) 22771888",
                "Hotel modern",
            ],
            [
                20,
                "The Ritz-Carlton Pacific Place",
                "rc.jktpp.res@ritzcarlton.com",
                "Jakarta",
                "(021) 25501888",
                "Hotel SCBD",
            ],
        ];

        foreach ($hotels as $h) {
            $hotel = new Hotel();
            $hotel->setNamaHotel($h[1]);
            $hotel->setEmail($h[2]);
            $hotel->setAlamat($h[3]);
            $hotel->setKontak($h[4]);
            $hotel->setDeskripsi($h[5]);

            $manager->persist($hotel);
            $this->addReference("hotel-" . $h[0], $hotel);
        }

        $manager->flush();
    }
}
