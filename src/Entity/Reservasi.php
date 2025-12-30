<?php

namespace App\Entity;

use App\Repository\ReservasiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservasiRepository::class)]
class Reservasi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $tanggalReservasi = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $tanggalCheckIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $tanggalCheckOut = null;

    #[ORM\Column]
    private ?int $jumlahKamar = null;

    #[ORM\Column]
    private ?int $totalMalam = null;

    #[ORM\ManyToOne(inversedBy: "reservasis")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: "reservasis")]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipeKamar $kamar = null;

    #[ORM\ManyToOne]
    private ?Pembayaran $pembayaran = null;

    #[ORM\ManyToOne]
    private ?Pembatalan $pembatalan = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTanggalReservasi(): ?\DateTime
    {
        return $this->tanggalReservasi;
    }

    public function setTanggalReservasi(\DateTime $tanggalReservasi): static
    {
        $this->tanggalReservasi = $tanggalReservasi;

        return $this;
    }

    public function getTanggalCheckIn(): ?\DateTime
    {
        return $this->tanggalCheckIn;
    }

    public function setTanggalCheckIn(\DateTime $tanggalCheckIn): static
    {
        $this->tanggalCheckIn = $tanggalCheckIn;

        return $this;
    }

    public function getTanggalCheckOut(): ?\DateTime
    {
        return $this->tanggalCheckOut;
    }

    public function setTanggalCheckOut(\DateTime $tanggalCheckOut): static
    {
        $this->tanggalCheckOut = $tanggalCheckOut;

        return $this;
    }

    public function getJumlahKamar(): ?int
    {
        return $this->jumlahKamar;
    }

    public function setJumlahKamar(int $jumlahKamar): static
    {
        $this->jumlahKamar = $jumlahKamar;

        return $this;
    }

    public function getTotalMalam(): ?int
    {
        return $this->totalMalam;
    }

    public function setTotalMalam(int $totalMalam): static
    {
        $this->totalMalam = $totalMalam;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getKamar(): ?TipeKamar
    {
        return $this->kamar;
    }

    public function setKamar(?TipeKamar $kamar): static
    {
        $this->kamar = $kamar;

        return $this;
    }

    public function getPembayaran(): ?Pembayaran
    {
        return $this->pembayaran;
    }

    public function setPembayaran(?Pembayaran $pembayaran): static
    {
        $this->pembayaran = $pembayaran;

        return $this;
    }

    public function getPembatalan(): ?Pembatalan
    {
        return $this->pembatalan;
    }

    public function setPembatalan(?Pembatalan $pembatalan): static
    {
        $this->pembatalan = $pembatalan;

        return $this;
    }
}
