<?php

namespace App\Controller\Api;

use App\Resources\FilmDistributorResource;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pimcore\Model\DataObject\FilmDistributor\Listing as FilmDistributorListing;
use Pimcore\Model\DataObject\FilmDistributor;

class FilmDistributorController extends FrontendController
{
    /**
     * @Route("/distributors", name="distributor_index")
     * @param Request $request
     * @param string  $language
     * @return Response
     */
    public function index(Request $request, string $language): Response
    {
        return $this->json(['data' => FilmDistributorResource::collection(new FilmDistributorListing())]);
    }

    /**
     * @Route("/distributors/{distributor}", name="distributor_show")
     * @param Request         $request
     * @param string          $language
     * @param FilmDistributor $distributor
     * @return Response
     */
    public function show(Request $request, string $language, FilmDistributor $distributor): Response
    {
        $resource = new FilmDistributorResource($distributor);
        $resource->isCollectionRequest = false;

        return $this->json($resource);
    }
}
