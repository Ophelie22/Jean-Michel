<?php

namespace App\Controller;

use App\Dto\SearchFreelanceConsoDto;
use App\Entity\Freelance;
use App\Service\FreelanceSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/freelances", name: "freelances_")]
class FreelanceController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FreelanceSearchService $freelanceSearchService;

    public function __construct(EntityManagerInterface $entityManager, FreelanceSearchService $freelanceSearchService)
    {
        $this->entityManager = $entityManager;
        $this->freelanceSearchService = $freelanceSearchService;
    }

    #[Route("/index", name: "index_freelances")]
    public function indexFreelances(): Response
    {
        // Récupère tous les freelances de la base de données
        $freelances = $this->entityManager->getRepository(Freelance::class)->findAll();

        // Indexe chaque freelance dans Elasticsearch
        foreach ($freelances as $freelance) {
            $this->freelanceSearchService->indexFreelance($freelance);
        }

        return new Response('Freelances indexed successfully!', Response::HTTP_OK);
    }
    #[Route("/search", name: "freelance_search", methods: ["GET"])]
    public function search(#[MapQueryParameter] SearchFreelanceConsoDto $dto): JsonResponse
    {
        $freelanceConsos = $this->freelanceSearchService->searchFreelance($dto->query);
        return $this->json($freelanceConsos, Response::HTTP_OK, [], ["groups" => "freelance_conso"]);
    }
    //
    #[Route("/search-page", name: "search_page", methods: ["GET"])]
    public function searchPage(): Response
    {
        return $this->render('freelance/search.html.twig');
    }
}

// #[Route("/freelances", name: "freelances_")]
// class FreelanceController extends AbstractController
// {
//     public function __construct(
//         private readonly FreelanceSearchService $freelanceSearchService
//     ) {
//     }

//     #[Route('/search', name: 'freelance_search', methods: ['GET'])]
//     public function search(Request $request): JsonResponse
//     {
//         $query = $request->query->get('q', '*');
//         $results = $this->freelanceSearchService->searchFreelance($query);
        
//         return $this->json($results);
//     }

//     // route pour la page de recherche
//     #[Route(name: "search_page", path: "/search", methods: ["GET"])]
//     public function searchPage(): Response
//     {
//         return $this->render('freelance/search.html.twig');
//     }
// }