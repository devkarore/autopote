<?php
// src/Controller/CategoryController.php

namespace App\Controller;

use App\DTO\CreateCategoryDTO;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    #[Route('/api/categories', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        #[MapRequestPayload] CreateCategoryDTO $dto,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): JsonResponse {

        // À ce stade, $dto est déjà validé.
        // Sinon, on reçoit une 422 en réponse HTTP

        $category = new Category();
        $category->setName($dto->name);
        $category->setDescription($dto->description);

        // On génère le slug ici puisqu'on a interdit à l'utilisateur de l'envoyer
        $slug = strtolower($slugger->slug($dto->name));
        $category->setSlug($slug);

        // Et on persiste la catégorie
        $em->persist($category);
        $em->flush();

        // Puis on renvoie l'objet créé en lui passant le groupe de lecture : il saura quelles propriétés donner ! 
        return $this->json($category, 201, [], ['groups' => ['category:read']]);
    }
}
