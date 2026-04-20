<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCategoryDTO
{
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    public string $name;

    #[Assert\Length(
        max: 500,
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères.'
    )]
    public ?string $description = null;
}
