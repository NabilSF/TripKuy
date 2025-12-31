<?php

namespace App\Entity;

use App\Repository\GambarKamarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GambarKamarRepository::class)]
class GambarKamar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\ManyToOne(inversedBy: 'gambarKamars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipeKamar $TipeKamar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getTipeKamar(): ?TipeKamar
    {
        return $this->TipeKamar;
    }

    public function setTipeKamar(?TipeKamar $TipeKamar): static
    {
        $this->TipeKamar = $TipeKamar;

        return $this;
    }
}
