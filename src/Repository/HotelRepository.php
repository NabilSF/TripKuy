<?php

namespace App\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Hotel;
use App\Entity\TipeKamar;

class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    public function findHotelWithMinHargaKamar(): array
    {
        $em = $this->getEntityManager();

        $subQb = $em
            ->createQueryBuilder()
            ->select("MIN(tk2.harga)")
            ->from(TipeKamar::class, "tk2")
            ->where("tk2.hotel = h");

        $qb = $this->createQueryBuilder("h");

        $qb->leftJoin(
            "h.tipeKamars",
            "tk",
            Join::WITH,
            $qb->expr()->eq("tk.harga", "(" . $subQb->getDQL() . ")"),
        )
            ->addSelect("tk")
            ->orderBy("h.id", "ASC");

        return $qb->getQuery()->getResult();
    }

    public function findByAlamat(?string $alamat): array
    {
        $qb = $this->createQueryBuilder("h");

        if ($alamat) {
            $qb->andWhere("h.alamat LIKE :alamat")->setParameter(
                "alamat",
                "%" . $alamat . "%",
            );
        }

        return $qb->getQuery()->getResult();
    }
}
