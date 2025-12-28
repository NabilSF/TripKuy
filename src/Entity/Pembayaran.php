<?php

namespace App\Entity;

use App\Repository\PembayaranRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PembayaranRepository::class)]
class Pembayaran
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalHarga = null;

    #[ORM\Column(length: 255)]
    private ?string $tipePembayaran = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalHarga(): ?int
    {
        return $this->totalHarga;
    }

    public function setTotalHarga(int $totalHarga): static
    {
        $this->totalHarga = $totalHarga;

        return $this;
    }

    public function getTipePembayaran(): ?string
    {
        return $this->tipePembayaran;
    }

    public function setTipePembayaran(string $tipePembayaran): static
    {
        $this->tipePembayaran = $tipePembayaran;

        return $this;
    }
}
