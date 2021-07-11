<?php

namespace App\Entity;

use App\Repository\FanfikRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FanfikRepository::class)
 */
class Fanfik
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainimg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMainimg(): ?string
    {
        return $this->mainimg;
    }

    public function setMainimg(string $mainimg): self
    {
        $this->mainimg = $mainimg;

        return $this;
    }
}
