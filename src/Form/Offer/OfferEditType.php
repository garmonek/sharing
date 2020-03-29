<?php
/**
 * @license MIT
 */

namespace App\Form\Offer;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OfferEditType
 * @package App\Form\Offer
 */
class OfferEditType extends OfferType
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @noinspection PhpUnusedParameterInspection
     */
    protected function addActiveField(FormBuilderInterface $builder)
    {
        $builder->add('active', CheckboxType::class, [
            'required' => false,
        ]);
    }
}
