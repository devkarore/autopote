<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;

#[ORM\Entity(repositoryClass: PartRepository::class)]
#[ORM\Table(name: 'part')]
#[ApiResource(
    paginationItemsPerPage: 12,
    operations: [
        new GetCollection(),
        new Get(),
    ],
    normalizationContext: ['groups' => ['part:read']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'brand' => 'exact',
    'category' => 'exact',
    'name' => 'partial'
])]

#[ApiFilter(BooleanFilter::class, properties: ['isAvailable'])]
#[ApiFilter(OrderFilter::class, properties: ['price'])]

class Part
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['part:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Le nom de la pièce est obligatoire.')]
    #[Assert\Length(max: 150, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.')]
    #[Groups(['part:read', 'brand:read', 'category:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank(message: 'La référence est obligatoire.')]
    #[Groups(['part:read'])]
    private ?string $reference = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['part:read'])]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull(message: 'Le prix est obligatoire.')]
    #[Assert\PositiveOrZero(message: 'Le prix doit être positif ou nul.')]
    #[Groups(['part:read'])]
    private ?string $price = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message: 'Le stock est obligatoire.')]
    #[Assert\PositiveOrZero(message: 'Le stock doit être positif ou nul.')]
    #[Groups(['part:read'])]
    private ?int $stock = 0;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['part:read'])]
    private ?string $part_condition = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['part:read'])]
    private ?bool $isAvailable = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['part:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'parts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'La marque est obligatoire.')]
    #[Groups(['part:read'])]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(inversedBy: 'parts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'La catégorie est obligatoire.')]
    #[Groups(['part:read'])]
    private ?Category $category = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isAvailable = true;
        $this->stock = 0;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPartCondition(): ?string
    {
        return $this->part_condition;
    }

    public function setPartCondition(?string $part_condition): static
    {
        $this->part_condition = $part_condition;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(?bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
