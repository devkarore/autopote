<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'category')]
#[ApiResource(    
    operations: [
        new GetCollection(),
        new Get(),
    ],
    normalizationContext: ['groups' => ['category:read']]
    )]
// #[ApiFilter(SearchFilter::class, properties: [

//     'name' => 'partial'
//     ])]

class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.')]
    #[Groups(['category:read', 'part:read'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['category:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['category:read'])]
    private ?string $slug = null;

    /**
     * @var Collection<int, Part>
     */
    #[Groups(['category:read'])]
    #[ORM\OneToMany(targetEntity: Part::class, mappedBy: 'category')]
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

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
            $part->setCategory($this);
        }

        return $this;
    }

    public function removePart(Part $part): static
    {
        if ($this->parts->removeElement($part)) {
            if ($part->getCategory() === $this) {
                $part->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
