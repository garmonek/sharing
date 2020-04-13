<?php

namespace App\Search;

use App\Entity\City;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label' => 'offer.search.tags.label',
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
                'placeholder' => 'offer.search.tags.placeholder',
            ]);
        }
        if ($options[self::ENABLE_EXCHANGE_TAGS]) {
            $builder->add('exchangeTags', Select2EntityType::class, [
                'label' => 'offer.search.exchange_tags.label',
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
                'placeholder' => 'offer.search.exchange_tags.placeholder',
            ]);
        }

        $builder->add('city', EntityType::class, [
            'label' => 'offer.search.city.label',
            'class' => City::class,
            'choice_label' => 'name',
            'required'   => false,
            'empty_data' => null,
            'placeholder' => 'offer.search.city.placeholder.all',
        ]);
// todo change or remove
//        $builder->add('districts', Select2EntityType::class, [
//            'label' => 'offer.search.districts.label',
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
//            'placeholder' => 'offer.search.districts.placeholder',
//        ]);

        if ($options[self::ENABLE_ACTIVE]) {
            $builder->add('active', ChoiceType::class, [
                'label' => 'offer.search.active.label',
                'expanded' => true,
                'multiple' => false,
                'data' => OfferCriteria::OFFER_ACTIVE,
                'choices' => [
                    'offer.search.active.yes.label' => OfferCriteria::OFFER_ACTIVE,
                    'offer.search.active.no.label' => OfferCriteria::OFFER_INACTIVE,
                    'offer.search.active.all.label' => OfferCriteria::OFFER_ALL,
                ],
            ]);
        }

        $builder->add('sortDirection', ChoiceType::class, [
            'label' => 'offer.search.sortDirection.label',
            'choices' => [
                'offer.search.sortDirection.asc.label' => OfferCriteria::SORT_DIRECTION_ASC,
                'offer.search.sortDirection.desc.label' => OfferCriteria::SORT_DIRECTION_DESC,
            ],
        ])
        ->add('sortValue', ChoiceType::class, [
            'label' => 'offer.search.sortValue.label',
            'choices' => [
                'offer.search.sortValue.createdAt.label' => OfferCriteria::SORT_VALUE_CREATED,
                'offer.search.sortValue.updatedAt.label' => OfferCriteria::SORT_VALUE_UPDATED,
                'offer.search.sortValue.exchangeTags.label' => OfferCriteria::SORT_VALUE_EXCHANGE_TAGS,
                'offer.search.sortValue.tags.label' => OfferCriteria::SORT_VALUE_TAGS,
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
