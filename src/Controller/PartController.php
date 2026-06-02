<?php
// src/Controller/PartController.php

namespace App\Controller;

use App\DTO\CreatePartDTO;
use App\Entity\Part;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PartController extends AbstractController
{
    #[Route('/api/parts', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        #[MapRequestPayload] CreatePartDTO $dto,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository
    ): JsonResponse {

        $category = $categoryRepository->find($dto->categoryId);
        if (!$category) {
            throw new UnprocessableEntityHttpException(
                "La catégorie {$dto->categoryId} n'existe pas."
            );
        }

        $brand = $brandRepository->find($dto->brandId);
        if (!$brand) {
            throw new UnprocessableEntityHttpException(
                "La marque {$dto->brandId} n'existe pas."
            );
        }

        $part = new Part();
        $part->setName($dto->name);
        $part->setReference(strtolower(str_replace(" ", "-", $dto->name)));
        $part->setPrice(0.0); // decimal en BDD = string en PHP
        $part->setStock(0);
        $part->setDescription($dto->description);
        $part->setPartCondition("");
        $part->setIsAvailable(false);
        $part->setCategory($category);
        $part->setBrand($brand);

        $em->persist($part);
        $em->flush();

        return $this->json($part, 201, [], ['groups' => ['part:read']]);
    }
}