<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;



#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post()
    ],
    normalizationContext: ['groups' => ['brand:read']],
    denormalizationContext: ['groups' => ['brand:write']]
)]

// #[ApiFilter(SearchFilter::class, properties: [
//     'name' => 'partial'
// ])]

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ORM\Table(name: 'brand')]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['brand:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom de la marque est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.')]
    #[Groups(['brand:read', 'brand:write', 'part:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['brand:read', 'brand:write', 'part:read'])]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['brand:read', 'brand:write'])]
    private ?string $logoUrl = null;

    /**
     * @var Collection<int, Part>
     */
    #[ORM\OneToMany(targetEntity: Part::class, mappedBy: 'brand')]
    #[Groups(['brand:read'])]
    private Collection $parts;

    public function __construct()
    {
        $this->parts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function setLogoUrl(?string $logoUrl): static
    {
        $this->logoUrl = $logoUrl;

        return $this;
    }

    /**
     * @return Collection<int, Part>
     */
    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(Part $part): static
    {
        if (!$this->parts->contains($part)) {
            $this->parts->add($part);
            $part->setBrand($this);
        }

        return $this;
    }

    public function removePart(Part $part): static
    {
        if ($this->parts->removeElement($part)) {
            if ($part->getBrand() === $this) {
                $part->setBrand(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
