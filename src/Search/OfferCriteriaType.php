<?php

namespace App\Search;

use App\Entity\District;
use App\Entity\Tag;
use App\Form\Offer\OfferType;
use App\Search\OfferCriteria;
use App\Search\CriteriaTagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class OfferCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', Select2EntityType::class, [
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
                    'tag_separators' => '[","a]',
                ],
                'placeholder' => 'form.offer.tags.placeholder',
            ])
            ->add('exchangeTags', Select2EntityType::class, [
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
            ])
            ->add('districts', Select2EntityType::class, [
                'label' => 'form.offer.districts.label',
                'multiple' => true,
                'remote_route' => 'search_district_autocomplete',
                'class' => District::class,
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
                'placeholder' => 'form.offer.districts.placeholder',
            ])
            ->add('active', ChoiceType::class, [
                'label' => 'form.offer.active.label',
                'choices' => [
                    'form.offer.active.yes.label' => OfferCriteria::OFFER_ACTIVE,
                    'form.offer.active.no.label' => OfferCriteria::OFFER_INACTIVE,
                    'form.offer.active.all.label' => OfferCriteria::OFFER_ALL,
                ]
            ])
            ->add('sortDirection', ChoiceType::class, [
                'label' => 'form.offer.sortDirection.label',
                'choices' => [
                    'form.offer.sortDirection.asc.label' => OfferCriteria::SORT_DIRECTION_ASC,
                    'form.offer.sortDirection.desc.label' => OfferCriteria::SORT_DIRECTION_DESC
                ]
            ])
            ->add('sortValue', ChoiceType::class, [
                'label' => 'form.offer.sortValue.label',
                'choices' => [
                    'form.offer.sortValue.createdAt.label' => OfferCriteria::SORT_VALUE_CREATED,
                    'form.offer.sortValue.updatedAt.label' => OfferCriteria::SORT_VALUE_UPDATED,
                    'form.offer.sortValue.exchangeTags.label' => OfferCriteria::SORT_VALUE_EXCHANGE_TAGS,
                    'form.offer.sortValue.tags.label' => OfferCriteria::SORT_VALUE_TAGS,
                ]
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OfferCriteria::class,
            'csrf_protection' => false
        ]);
    }
}
