<?php
// src/DTO/CreatePartDTO.php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePartDTO
{
    #[Assert\NotBlank(message: 'Le nom de la pièce est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    public string $name;

    #[Assert\Length(max: 500)]
    public ?string $description = null;


    #[Assert\NotBlank(message: 'La catégorie est obligatoire.')]
    #[Assert\Positive(message: "L'identifiant de catégorie est invalide.")]
    public int $categoryId;

    #[Assert\NotBlank(message: 'La marque est obligatoire.')]
    #[Assert\Positive(message: "L'identifiant de marque est invalide.")]
    public int $brandId;
}