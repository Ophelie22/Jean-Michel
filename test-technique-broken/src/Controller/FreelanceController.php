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
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FreelanceSearchService $freelanceSearchService
    ) {}

    #[Route("/index", name: "index_freelances")]
    public function indexFreelances(): JsonResponse
    {
        $freelances = $this->entityManager->getRepository(Freelance::class)->findAll();
        $results = $this->freelanceSearchService->indexAllFreelances($freelances);

        return $this->json([
            'message' => 'Indexation terminée',
            'stats' => $results
        ]);
    }

    #[Route("/search", name: "freelance_search", methods: ["GET"])]
    public function search(#[MapQueryParameter] string $query = '*'): JsonResponse
    {
        $freelanceConsos = $this->freelanceSearchService->searchFreelance($query);
        return $this->json($freelanceConsos);
    }

    // //
    // #[Route("/search-page", name: "search_page", methods: ["GET"])]
    // public function searchPage(): Response
    // {
    //     return $this->render('freelance/search.html.twig');
    // }
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