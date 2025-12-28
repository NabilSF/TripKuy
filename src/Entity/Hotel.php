<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $namaHotel = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $alamat = null;

    #[ORM\Column(length: 255)]
    private ?string $kontak = null;

    #[ORM\Column(length: 255)]
    private ?string $deskripsi = null;

    /**
     * @var Collection<int, TipeKamar>
     */
    #[ORM\OneToMany(targetEntity: TipeKamar::class, mappedBy: 'hotel')]
    private Collection $tipeKamars;

    public function __construct()
    {
        $this->tipeKamars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamaHotel(): ?string
    {
        return $this->namaHotel;
    }

    public function setNamaHotel(string $namaHotel): static
    {
        $this->namaHotel = $namaHotel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    public function setAlamat(string $alamat): static
    {
        $this->alamat = $alamat;

        return $this;
    }

    public function getKontak(): ?string
    {
        return $this->kontak;
    }

    public function setKontak(string $kontak): static
    {
        $this->kontak = $kontak;

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

    /**
     * @return Collection<int, TipeKamar>
     */
    public function getTipeKamars(): Collection
    {
        return $this->tipeKamars;
    }

    public function addTipeKamar(TipeKamar $tipeKamar): static
    {
        if (!$this->tipeKamars->contains($tipeKamar)) {
            $this->tipeKamars->add($tipeKamar);
            $tipeKamar->setHotel($this);
        }

        return $this;
    }

    public function removeTipeKamar(TipeKamar $tipeKamar): static
    {
        if ($this->tipeKamars->removeElement($tipeKamar)) {
            // set the owning side to null (unless already changed)
            if ($tipeKamar->getHotel() === $this) {
                $tipeKamar->setHotel(null);
            }
        }

        return $this;
    }
}
