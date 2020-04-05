<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AirportRepository")
 */
class Airport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity="Flight", mappedBy="to")
     */
    private $inbound;

    /**
     * @ORM\OneToMany(targetEntity="Flight", mappedBy="from")
     */
    private $outbound;

    public function __toString()
    {
      return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $input): self
    {
        $this->name = $input;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $input): self
    {
        $this->region = $input;
        return $this;
    }
}
