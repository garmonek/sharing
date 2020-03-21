<?php /** @noinspection MessDetectorValidationInspection */
/** @noinspection PhpUnusedParameterInspection */

/**
 * @license MIT
 */

namespace App\Form\Offer;

use App\Entity\District;
use App\Entity\Offer;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Class OfferType
 */
class OfferType extends AbstractType
{
    /**
     * @var string
     */
    public const CITY_DISTRICT_DELIMITER = '::';

    /**
     * @var OfferUserTransformer
     */
    private $userTransformer;

    /**
     * OfferType constructor.
     * @param OfferUserTransformer $userTransformer
     */
    public function __construct(OfferUserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //todo set district as one for whole form
                //todo form should contain many offers with same district
            ->add('district', Select2EntityType::class, [
                'label' => 'form.city.districts.label',
                'multiple' => false,
                'remote_route' => 'json_offer_districts',
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
                'placeholder' => 'form.city.placeholder',
            ])
            ->add('description', TextareaType::class)
            ->add('tags', Select2EntityType::class, [
                'label' => 'form.offer.tags.label',
                'multiple' => true,
                'remote_route' => 'json_city_districts',
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
                    'enabled' => true,
                    'new_tag_text' => '',
                    'tag_separators' => '[","]',
                ],
                'placeholder' => 'form.offer.tags.placeholder',
            ])
            ->add('exchangeTags', Select2EntityType::class, [
                'label' => 'form.offer.tags.label',
                'multiple' => true,
                'remote_route' => 'json_city_districts',
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
                    'enabled' => true,
                    'new_tag_text' => '',
                    'tag_separators' => '[","]',
                ],
                'placeholder' => 'form.offer.tags.placeholder',
            ])->add('images', FileType::class, [
                'multiple' => true,
                'attr' => [
                    'class' => 'js-jpreview',
                    'data-jpreview-container'=> '#demo-1-container',
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                ]
            ])
            ->add('user', HiddenType::class);

        $builder->get('user')->addModelTransformer(
            $this->userTransformer
        );

        $this->addActiveField($builder);
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

    /**
     * @param FormBuilderInterface $builder
     *
     * @noinspection PhpUnusedParameterInspection
     */
    protected function addActiveField(FormBuilderInterface $builder)
    {
        $builder->add('active', HiddenType::class);

        $builder->get('active')->addModelTransformer(new CallbackTransformer(
            function (?bool $dbValue) {
                return $dbValue;
            },
            function (?string $formValue) {
                return true;
            }
        ));
    }
}
