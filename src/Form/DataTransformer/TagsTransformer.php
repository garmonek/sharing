<?php

namespace App\Form\DataTransformer;

use App\Entity\AbstractTimestampableEntity;
use Tetranz\Select2EntityBundle\Form\DataTransformer\EntitiesToPropertyTransformer;

/**
 * Data transformer for multiple mode (i.e., multiple = true)
 *
 * Class EntitiesToPropertyTransformer
 *
 */
class TagsTransformer extends EntitiesToPropertyTransformer
{
    /**
     * Transform array to a collection of entities
     *
     * @param array $values
     *
     * @return array
     */
    public function reverseTransform($values)
    {
        if (!is_array($values) || empty($values)) {
            return [];
        }

        $cleanValues = array_map(function (string $value) {
            $value = strtolower($value);

            return str_replace($this->newTagPrefix, '', $value);
        }, $values);

        /** @noinspection PhpUndefinedMethodInspection */
        $entities = $this->em->createQueryBuilder()
            ->select('entity')
            ->from($this->className, 'entity')
            ->where('entity.'.$this->primaryKey.' IN (:ids)')
            ->setParameter('ids', $cleanValues)
            ->getQuery()
            ->getResult();

        $entitiesNames = array_map(function (AbstractTimestampableEntity $tag) {
            return $tag->getName();
        }, $entities);

        $newTags = array_map(function (string $name) {
            $object = new $this->className;
            $this->accessor->setValue($object, $this->textProperty, $name);

            return $object;
        }, array_diff($cleanValues, $entitiesNames));

        return array_merge($newTags, $entities);
    }
}
