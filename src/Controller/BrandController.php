<?php
// src/Controller/CategoryController.php

namespace App\Controller;

use App\DTO\CreateBrandDTO;
use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class BrandController extends AbstractController
{
    #[Route('/api/brands', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        #[MapRequestPayload] CreateBrandDTO $dto,
        EntityManagerInterface $em,
     ): JsonResponse {

        // À ce stade, $dto est déjà validé.
        // Sinon, on reçoit une 422 en réponse HTTP

        $brand = new Brand();
        $brand->setName($dto->name);
        $brand->setCountry($dto->country);

        // Et on persiste la catégorie
        $em->persist($brand);
        $em->flush();

        // Puis on renvoie l'objet créé en lui passant le groupe de lecture : il saura quelles propriétés donner ! 
        return $this->json($brand, 201, [], ['groups' => ['brand:read']]);
    }
}
