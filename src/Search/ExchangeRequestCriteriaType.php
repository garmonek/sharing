<?php
/**
 * @license MIT
 */

namespace App\Search;


use App\Entity\City;
use App\Entity\Image;
use App\Entity\Tag;
use App\Form\DataTransformer\TagsTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ExchangeRequestCriteriaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'form.city.label',
                'empty_data' => null,
                'required' => false,
                'placeholder' => 'form.label.all'
            ])
            ->add('filterUsing', ChoiceType::class, [
                'choices' => [
                    'form.filterUsing.target' => ExchangeRequestCriteria::FILTER_USING_TARGET,
                    'form.filterUsing.proposals' => ExchangeRequestCriteria::FILTER_USING_PROPOSALS,
                ],
            ])
            ->add('tags', Select2EntityType::class, [
                'label' => 'form.offer.tags.label',
                'multiple' => true,
                'remote_route' => 'exchange_request_tag_autocomplete',
                'class' => Tag::class,
                'property' => 'name',
                'primary_key' => 'name',
                'text_property' => 'name',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000,
                'language' => 'en',
                'width' => '100%',
                'transformer' => TagsTransformer::class,
                'placeholder' => 'form.offer.tags.placeholder',
            ])
            ->add('useExchangeTags', CheckboxType::class, [
                'label' => 'form.useExchangeTags.label',
                'required' => false
            ])
            ->setMethod('GET');
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExchangeRequestCriteria::class,
            'csrf_protection' => false,
        ]);
    }


}
