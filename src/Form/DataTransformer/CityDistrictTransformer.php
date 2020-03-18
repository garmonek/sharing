<?php
/**
 * @license MIT
 */

namespace App\Form\DataTransformer;

use App\Entity\District;
use Tetranz\Select2EntityBundle\Form\DataTransformer\EntitiesToPropertyTransformer;

/**
 * Class CityDistrictTransformer
 */
class CityDistrictTransformer extends EntitiesToPropertyTransformer
{
    /**
     * @param array $values
     * @return array
     */
    public function reverseTransform($values): array
    {
        return array_map(function(District $district) {
            return $district->setName(strtolower($district->getName()));
        }, parent::reverseTransform($values));
    }
}
