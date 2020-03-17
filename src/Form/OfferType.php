<?php
/**
 * @license MIT
 */

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OfferType
 */
class OfferType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //todo set district as one for whole form
                //todo form should contain many offers with same district
            ->add('district')
            ->add('description')
            //todo active as true on offer create, can edit on edit
//            ->add('tags')
//            ->add('exchangeTags')
//            ->add('images')
//            ->add('webImages')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
