<?php

namespace App\Search;

use App\Entity\City;
use App\Entity\District;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Class OfferCriteriaType
 * @package App\Search
 */
class OfferCriteriaType extends AbstractType
{
    public const ENABLE_TAGS = 'en_tag';
    public const ENABLE_EXCHANGE_TAGS = 'en_ex_tag';
    public const ENABLE_ACTIVE = 'en_act';

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options[self::ENABLE_TAGS]) {
            $builder->add('tags', Select2EntityType::class, [
                'label' => 'form.offer.tags.label',
                'multiple' => true,
                'remote_route' => 'search_tag_autocomplete',
                'class' => Tag::class,
                'property' => 'name',
                'primary_key' => 'id',
                'text_property' => 'name',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000,
                'language' => 'en',
                'width' => '100%',
                'allow_add' => [
                    'enabled' => false,
                    'new_tag_text' => '',
                    'tag_separators' => '[","]',
                ],
                'placeholder' => 'form.offer.tags.placeholder',
            ]);
        }
        if ($options[self::ENABLE_EXCHANGE_TAGS]) {
            $builder->add('exchangeTags', Select2EntityType::class, [
                'label' => 'form.offer.exchange_tags.label',
                'multiple' => true,
                'remote_route' => 'search_tag_autocomplete',
                'class' => Tag::class,
                'property' => 'name',
                'primary_key' => 'id',
                'text_property' => 'name',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000,
                'language' => 'en',
                'width' => '100%',
                'allow_add' => [
                    'enabled' => false,
                    'new_tag_text' => '',
                    'tag_separators' => '[","]',
                ],
                'placeholder' => 'form.offer.exchange_tags.placeholder',
            ]);
        }

        $builder->add('city', EntityType::class, [
            'label' => 'form.offer.city.label',
            'class' => City::class,
            'choice_label' => 'name',
            'required'   => false,
            'empty_data' => null,
            'placeholder' => 'form.offer.city.placeholder.all'
        ]);

//        $builder->add('districts', Select2EntityType::class, [
//            'label' => 'form.offer.districts.label',
//            'multiple' => true,
//            'remote_route' => 'search_district_autocomplete',
//            'class' => District::class,
//            'property' => 'name',
//            'primary_key' => 'id',
//            'text_property' => 'name',
//            'minimum_input_length' => 2,
//            'page_limit' => 10,
//            'allow_clear' => true,
//            'delay' => 250,
//            'cache' => true,
//            'cache_timeout' => 60000,
//            'language' => 'en',
//            'width' => '100%',
//            'allow_add' => [
//                'enabled' => false,
//                'new_tag_text' => '',
//                'tag_separators' => '[","]',
//            ],
//            'placeholder' => 'form.offer.districts.placeholder',
//        ]);

        if ($options[self::ENABLE_ACTIVE]) {
            $builder->add('active', ChoiceType::class, [
                'label' => 'form.offer.active.label',
                'choices' => [
                    'form.offer.active.yes.label' => OfferCriteria::OFFER_ACTIVE,
                    'form.offer.active.no.label' => OfferCriteria::OFFER_INACTIVE,
                    'form.offer.active.all.label' => OfferCriteria::OFFER_ALL,
                ],
            ]);
        }

        $builder->add('sortDirection', ChoiceType::class, [
            'label' => 'form.offer.sortDirection.label',
            'choices' => [
                'form.offer.sortDirection.asc.label' => OfferCriteria::SORT_DIRECTION_ASC,
                'form.offer.sortDirection.desc.label' => OfferCriteria::SORT_DIRECTION_DESC,
            ],
        ])
        ->add('sortValue', ChoiceType::class, [
            'label' => 'form.offer.sortValue.label',
            'choices' => [
                'form.offer.sortValue.createdAt.label' => OfferCriteria::SORT_VALUE_CREATED,
                'form.offer.sortValue.updatedAt.label' => OfferCriteria::SORT_VALUE_UPDATED,
                'form.offer.sortValue.exchangeTags.label' => OfferCriteria::SORT_VALUE_EXCHANGE_TAGS,
                'form.offer.sortValue.tags.label' => OfferCriteria::SORT_VALUE_TAGS,
            ],
        ])
        ->setMethod('GET');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OfferCriteria::class,
            'csrf_protection' => false,
            self::ENABLE_TAGS => true,
            self::ENABLE_EXCHANGE_TAGS => true,
            self::ENABLE_ACTIVE => true,
        ]);
    }
}
