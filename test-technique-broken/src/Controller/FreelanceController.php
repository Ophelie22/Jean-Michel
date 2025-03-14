<?php

namespace App\Controller;

use App\Dto\SearchFreelanceConsoDto;
use App\Service\FreelanceSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route("/freelances", name: "freelances_")]
class FreelanceController extends AbstractController
{
    public function __construct(
        private readonly FreelanceSearchService $freelanceSearchService
    ) {
    }

    #[Route('/search', name: 'freelance_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '*');
        $results = $this->freelanceSearchService->searchFreelance($query);
        
        return $this->json($results);
    }

    // route pour la page de recherche
    #[Route(name: "search_page", path: "/search", methods: ["GET"])]
    public function searchPage(): Response
    {
        return $this->render('freelance/search.html.twig');
    }
}