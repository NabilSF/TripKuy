<?php

namespace App\DataFixtures;

use App\Entity\GambarHotel;
use App\Entity\GambarKamar;
use App\Entity\Hotel;
use App\Entity\TipeKamar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
                    "Resor tepi pantai yang memadukan keanggunan modern dengan tradisi spiritual Bali, terletak di atas tebing Sawangan dengan pemandangan Samudra Hindia yang tak bertepi.",
            ],
            [
                "nama" => "Padma Resort",
                "lokasi" => "Bandung",
                "email" => "reservation@padmaresort.com",
                "deskripsi" =>
                    "Terletak di lembah hijau Ciumbuleuit yang spektakuler, tempat ini adalah pelarian sempurna untuk menikmati udara pegunungan yang segar dan kolam renang air hangat ikonik.",
            ],
            [
                "nama" => "Hotel Indonesia Kempinski",
                "lokasi" => "Jakarta",
                "email" => "info.jakarta@kempinski.com",
                "deskripsi" =>
                    "Hotel bersejarah pertama di Indonesia yang menggabungkan warisan budaya nusantara dengan standar kemewahan Eropa yang tak tertandingi.",
            ],
            [
                "nama" => "Amanjiwo",
                "lokasi" => "Magelang",
                "email" => "amanjiwo@aman.com",
                "deskripsi" =>
                    "Sebuah mahakarya arsitektur yang terinspirasi dari stupa Candi Borobudur, menawarkan ketenangan spiritual di tengah amfiteater alami perbukitan Menoreh.",
            ],
            [
                "nama" => "Plataran Borobudur",
                "lokasi" => "Magelang",
                "email" => "borobudur@plataran.com",
                "deskripsi" =>
                    "Menghadirkan suasana pedesaan Jawa yang autentik dengan sentuhan kolonial, memberikan pengalaman menginap yang intim tepat di depan keajaiban dunia.",
            ],
            [
                "nama" => "Alila Villas Uluwatu",
                "lokasi" => "Bali",
                "email" => "uluwatu@alilahotels.com",
                "deskripsi" =>
                    "Pelopor desain berkelanjutan yang dramatis di tepi tebing kapur, menawarkan privasi total dalam vila modern yang seolah melayang di atas laut.",
            ],
            [
                "nama" => "Hotel Tentrem",
                "lokasi" => "Yogyakarta",
                "email" => "info@hoteltentrem.com",
                "deskripsi" =>
                    "Mengusung filosofi ketenangan jiwa, hotel ini mendefinisikan ulang keramahtamahan Jawa modern dengan fasilitas bintang lima yang megah.",
            ],
            [
                "nama" => "Pullman Vimala Hills",
                "lokasi" => "Bogor",
                "email" => "reservation@pullman-vimalahills.com",
                "deskripsi" =>
                    "Resor ramah keluarga dengan konsep kebun raya yang rimbun, menawarkan pemandangan Gunung Salak yang menenangkan di pagi hari.",
            ],
            [
                "nama" => "The Gaia Hotel",
                "lokasi" => "Bandung",
                "email" => "hello@thegaiahotel.com",
                "deskripsi" =>
                    "Destinasi gaya hidup aktif yang unik dengan perpustakaan raksasa yang artistik, dirancang untuk memberikan keseimbangan antara eksplorasi dan istirahat.",
            ],
        ];

        foreach ($hotels as $index => $data) {
            $hotel = new Hotel();
            $hotel->setNamaHotel($data["nama"]); //
            $hotel->setAlamat($data["lokasi"]); //
            $hotel->setEmail($data["email"]); //
            $hotel->setKontak("021-" . rand(111, 999) . rand(1000, 9999)); //
            $hotel->setDeskripsi($data["deskripsi"]); //

            // Tambahkan Gambar Hotel (2 per hotel)
            for ($j = 1; $j <= 2; $j++) {
                $hImage = new GambarHotel();
                $hImage->setFileName("hotel_" . ($index + 1) . "_$j.jpg");
                $hImage->setHotel($hotel);
                $manager->persist($hImage);
            }

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
                $tipe->setNamaKamar($roomData["name"]); //
                $tipe->setDeskripsi(
                    "Fasilitas premium dengan kenyamanan maksimal.",
                ); //
                $tipe->setKapasitasOrang(2); //
                $tipe->setTotalKamar(rand(5, 15)); //

                // LOGIKA HARGA BULAT:
                // Mengambil harga dasar dan menambahkan variasi kelipatan 50.000
                $hargaBulat = $roomData["base"] + rand(0, 10) * 50000;
                $tipe->setHarga($hargaBulat); //

                $tipe->setHotel($hotel); //

                // Tambahkan Gambar Kamar
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
