<?php

namespace App\Form;

use App\Entity\ExchangeRequest;
use App\Entity\Offer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExchangeRequestType
 *
 */
class ExchangeRequestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextType::class)
            ->add('proposals', EntityType::class, [
                'class' => Offer::class,
                'choice_label' => 'name',
                'multiple' => true,
                'choices' => $options['matchingOffers'],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExchangeRequest::class,
            'matchingOffers' => [],
        ]);
    }
}
