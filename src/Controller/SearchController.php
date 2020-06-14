<?php

namespace App\Controller;

use App\Service\NewsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @param NewsGenerator $newsGenerator
     * @return JsonResponse
     */
    public function index(Request $request, NewsGenerator $newsGenerator)
    {
        $keyword = $request->query->get('keyword');
        $response = $newsGenerator->searchNews($keyword);

        return new JsonResponse(['news' => $response]);
    }
}
