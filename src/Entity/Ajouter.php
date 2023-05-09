<?php

namespace App\Entity;

use App\Repository\AjouterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AjouterRepository::class)]
class Ajouter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Qte = null;

    #[ORM\ManyToOne(inversedBy: 'ajouters')]
    private ?Panier $panier = null;

    #[ORM\ManyToOne(inversedBy: 'ajouters')]
    private ?Articles $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQte(): ?int
    {
        return $this->Qte;
    }

    public function setQte(int $Qte): self
    {
        $this->Qte = $Qte;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getProduit(): ?Articles
    {
        return $this->produit;
    }

    public function setProduit(?Articles $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}
