<?php

namespace App\Controller\Api;

use App\Resources\MovieResource;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pimcore\Model\DataObject\Movie\Listing as MovieListing;

class MovieController extends FrontendController
{
    /**
     * @Route("/movies", name="movies_index")
     * @param Request $request
     * @param string  $language
     * @return Response
     */
    public function index(Request $request, string $language): Response
    {
        $page = $request->get('page', 1);
        $limit = 10;

        $movies = new MovieListing();
        $total = $movies->count();
        $movies
            ->setOffset(($page-1)*$limit)
            ->setLimit($limit);

        $lastPage = ceil($total / $limit);
        $uri = $request->getPathInfo();

        return $this->json([
            'data' => MovieResource::collection($movies, $language),
            'meta' => [
                'items'       => $total,
                'perPage'     => $limit,
                'currentPage' => $page,
                'pages'       => $lastPage,
                'nextPage'    => $page == $lastPage ? '' : rtrim($uri, '/').'/?page='.($page + 1),
                'prvPage'     => $page <= 1 ? '' : rtrim($uri, '/').'/?page='.($page - 1),
            ]
        ]);
    }
}
