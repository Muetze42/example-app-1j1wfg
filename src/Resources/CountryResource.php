<?php

namespace App\Resources;

use NormanHuth\HelpersPimcore\Http\Resources\JsonResource;
use Pimcore\Model\DataObject\Country;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property Country     $object
 * @property string|null $language
 */
class CountryResource extends JsonResource
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
            'name' => $this->object->getName($this->language),
            'code' => $this->object->getKey()
        ];
    }
}
