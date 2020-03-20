<?php
/**
 * @license MIT
 */

namespace App\Form;

use App\Entity\City;
use App\Entity\District;
use App\Form\DataTransformer\CityDistrictTransformer;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Class CityType
 */
class CityType extends AbstractType
{
    private const REQUEST_CITY_ID_KEY = 'cityId';

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cityId = $this->getCityId($options);
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.city.name.label',
            ])
            ->add('districts', Select2EntityType::class, [
                'label' => 'form.city.districts.label',
                'multiple' => true,
                'remote_route' => 'json_city_districts',
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
                  'enabled' => true,
                  'new_tag_text' => '',
                  'tag_separators' => '[","]',
                ],
                'query_parameters' => [
                    self::REQUEST_CITY_ID_KEY => $cityId,
                ],
                'callback' => function (QueryBuilder $queryBuilder, Request $data) {
                    $cityId = (int) $data->get(self::REQUEST_CITY_ID_KEY);
                    if ($cityId > 0) {
                        $queryBuilder->andWhere('e.city = :d')
                            ->setParameter('d', $cityId, ParameterType::INTEGER);
                    }
                },
                'transformer' => CityDistrictTransformer::class,
                'placeholder' => 'form.city.placeholder',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }

    /**
     * @param array $options
     *
     * @return int|null
     */
    private function getCityId(array $options): ?int
    {
        $city = $options['data'] ?? null;

        return $city instanceof City ? $city->getId() : null;
    }
}
