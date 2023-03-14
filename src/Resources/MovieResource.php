<?php

namespace App\Resources;

use NormanHuth\HelpersPimcore\Http\Resources\JsonResource;
use Symfony\Component\HttpFoundation\Request;
use Pimcore\Model\DataObject\Movie;

/**
 * @property Movie       $object
 * @property string|null $language
 */
class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->object->getId(),
            'title'       => $this->object->getTitle($this->language),
            'year'        => $this->object->getYear(),
            'countries'   => CountryResource::collection($this->object->getCountries(), $this->language),
            'distributor' => $this->object->getDistributor()[0]->getName(),
        ];
    }
}
