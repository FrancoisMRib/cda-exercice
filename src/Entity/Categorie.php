<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    operations: [
    new Get(
    uriTemplate: '/category/{id}',
    requirements: ['id' => '\d+'],
    normalizationContext: ['groups' => 'category:item']),
    new GetCollection(
    uriTemplate: '/category',
    normalizationContext: ['groups' => 'category:list']),
    ],
    order: ['id' => 'ASC', 'name' => 'ASC'],
    paginationEnabled: true
   )]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:list', 'category:item'])] 
    # [Groups(['api', 'admin'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['category:list', 'category:item'])] 
    # [Groups(['api', 'admin'])]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString() : string 
    {
        return $this->nom;
    }
}