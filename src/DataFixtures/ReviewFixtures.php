<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $reviews = [
            [2, "review_hotel1.jpg", 5, "Luar biasa!"],
            [3, "review_hotel2.jpg", 4, "Check-in agak lama"],
            [4, "review_hotel7.jpg", 5, "Kolam renang juara"],
            [5, "review_hotel14.jpg", 5, "Pengalaman terbaik"],
        ];

        foreach ($reviews as $r) {
            $review = new Review();
            $review->setUser($this->getReference("user-" . $r[0], User::class));
            $review->setGambar($r[1]);
            $review->setRating($r[2]);
            $review->setDeskripsi($r[3]);

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
