<?php

namespace App\Entity;

use App\Repository\TipeKamarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipeKamarRepository::class)]
class TipeKamar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $namaKamar = null;

    #[ORM\Column(length: 255)]
    private ?string $deskripsi = null;

    #[ORM\Column]
    private ?int $kapasitasOrang = null;

    #[ORM\Column]
    private ?int $totalKamar = null;

    #[ORM\Column]
    private ?int $harga = null;

    #[ORM\ManyToOne(inversedBy: 'tipeKamars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hotel $hotel = null;

    /**
     * @var Collection<int, Reservasi>
     */
    #[ORM\OneToMany(targetEntity: Reservasi::class, mappedBy: 'kamar')]
    private Collection $reservasis;

    /**
     * @var Collection<int, GambarKamar>
     */
    #[ORM\OneToMany(targetEntity: GambarKamar::class, mappedBy: 'TipeKamar')]
    private Collection $gambarKamars;

    public function __construct()
    {
        $this->reservasis = new ArrayCollection();
        $this->gambarKamars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamaKamar(): ?string
    {
        return $this->namaKamar;
    }

    public function setNamaKamar(string $namaKamar): static
    {
        $this->namaKamar = $namaKamar;

        return $this;
    }

    public function getDeskripsi(): ?string
    {
        return $this->deskripsi;
    }

    public function setDeskripsi(string $deskripsi): static
    {
        $this->deskripsi = $deskripsi;

        return $this;
    }

    public function getKapasitasOrang(): ?int
    {
        return $this->kapasitasOrang;
    }

    public function setKapasitasOrang(int $kapasitasOrang): static
    {
        $this->kapasitasOrang = $kapasitasOrang;

        return $this;
    }

    public function getTotalKamar(): ?int
    {
        return $this->totalKamar;
    }

    public function setTotalKamar(int $totalKamar): static
    {
        $this->totalKamar = $totalKamar;

        return $this;
    }

    public function getHarga(): ?int
    {
        return $this->harga;
    }

    public function setHarga(int $harga): static
    {
        $this->harga = $harga;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): static
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * @return Collection<int, Reservasi>
     */
    public function getReservasis(): Collection
    {
        return $this->reservasis;
    }

    public function addReservasi(Reservasi $reservasi): static
    {
        if (!$this->reservasis->contains($reservasi)) {
            $this->reservasis->add($reservasi);
            $reservasi->setKamar($this);
        }

        return $this;
    }

    public function removeReservasi(Reservasi $reservasi): static
    {
        if ($this->reservasis->removeElement($reservasi)) {
            // set the owning side to null (unless already changed)
            if ($reservasi->getKamar() === $this) {
                $reservasi->setKamar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GambarKamar>
     */
    public function getGambarKamars(): Collection
    {
        return $this->gambarKamars;
    }

    public function addGambarKamar(GambarKamar $gambarKamar): static
    {
        if (!$this->gambarKamars->contains($gambarKamar)) {
            $this->gambarKamars->add($gambarKamar);
            $gambarKamar->setTipeKamar($this);
        }

        return $this;
    }

    public function removeGambarKamar(GambarKamar $gambarKamar): static
    {
        if ($this->gambarKamars->removeElement($gambarKamar)) {
            // set the owning side to null (unless already changed)
            if ($gambarKamar->getTipeKamar() === $this) {
                $gambarKamar->setTipeKamar(null);
            }
        }

        return $this;
    }
}
