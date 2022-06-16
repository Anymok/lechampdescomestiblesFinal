<?php

namespace App\Entity;

use App\Repository\PanierPlantsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierPlantsRepository::class)
 */
class PanierPlants
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Panier::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Panier;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Plants::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Plants;

    /**
     * @ORM\Column(type="integer")
     */
    private $qt;

 

    public function getPanier(): ?Panier
    {
        return $this->Panier;
    }

    public function setPanier(?Panier $Panier): self
    {
        $this->Panier = $Panier;

        return $this;
    }

    public function getPlants(): ?Plants
    {
        return $this->Plants;
    }

    public function setPlants(?Plants $Plants): self
    {
        $this->Plants = $Plants;

        return $this;
    }

    public function getQt(): ?int
    {
        return $this->qt;
    }

    public function setQt(int $qt): self
    {
        $this->qt = $qt;

        return $this;
    }
}
