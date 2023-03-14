<?php

namespace App\Resources;

use NormanHuth\HelpersPimcore\Http\Resources\JsonResource;
use Symfony\Component\HttpFoundation\Request;
use Pimcore\Model\DataObject\FilmDistributor;
use Pimcore\Model\DataObject\Movie\Listing as MovieListing;

/**
 * @property FilmDistributor $object
 * @property string|null     $language
 */
class FilmDistributorResource extends JsonResource
{
    public bool $isCollectionRequest = true;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        if ($this->isCollectionRequest) {
            return [
                'id'     => $this->object->getId(),
                'name'   => $this->object->getName(),
                'detail' => $request->getPathInfo().'/'.$this->object->getId(),
            ];
        }

        return [
            'id'     => $this->object->getId(),
            'name'   => $this->object->getName(),
            'movies' => MovieResource::collection(
                (new MovieListing())
                    ->setCondition('distributor LIKE ?', [','.$this->object->getId().','])
            ),
        ];
    }
}
