<?php

namespace App\Entity;

use App\Repository\PembatalanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PembatalanRepository::class)]
class Pembatalan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $alasan = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $tanggalPengajuan = null;

    #[ORM\Column(length: 255)]
    private ?string $catatanAdmin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $tanggalRefund = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlasan(): ?string
    {
        return $this->alasan;
    }

    public function setAlasan(string $alasan): static
    {
        $this->alasan = $alasan;

        return $this;
    }

    public function getTanggalPengajuan(): ?\DateTime
    {
        return $this->tanggalPengajuan;
    }

    public function setTanggalPengajuan(\DateTime $tanggalPengajuan): static
    {
        $this->tanggalPengajuan = $tanggalPengajuan;

        return $this;
    }

    public function getCatatanAdmin(): ?string
    {
        return $this->catatanAdmin;
    }

    public function setCatatanAdmin(string $catatanAdmin): static
    {
        $this->catatanAdmin = $catatanAdmin;

        return $this;
    }

    public function getTanggalRefund(): ?\DateTime
    {
        return $this->tanggalRefund;
    }

    public function setTanggalRefund(\DateTime $tanggalRefund): static
    {
        $this->tanggalRefund = $tanggalRefund;

        return $this;
    }
}
