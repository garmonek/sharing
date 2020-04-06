<?php /** @noinspection MessDetectorValidationInspection */
/** @noinspection PhpUnusedParameterInspection */

/**
 * @license MIT
 */

namespace App\Form\Offer;

use App\Entity\District;
use App\Entity\Offer;
use App\Entity\Tag;
use App\Form\DataTransformer\TagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @var OfferImageTransformer
     */
    private $imageTransformer;

    /**
     * OfferType constructor.
     * @param OfferUserTransformer  $userTransformer
     * @param OfferImageTransformer $imageTransformer
     */
    public function __construct(OfferUserTransformer $userTransformer, OfferImageTransformer $imageTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->imageTransformer = $imageTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('district', Select2EntityType::class, [
                'label' => 'form.city.districts.label',
                'multiple' => false,
                'remote_route' => 'offer_district_autocomplete',
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
                'remote_route' => 'offer_tag_autocomplete',
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
                'allow_add' => [
                    'enabled' => true,
                    'new_tag_text' => '',
                    'tag_separators' => '[","]',
                ],
                'placeholder' => 'form.offer.tags.placeholder',
            ])
            ->add('exchangeTags', Select2EntityType::class, [
                'label' => 'form.offer.exchange.tags.label',
                'multiple' => true,
                'remote_route' => 'offer_tag_autocomplete',
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
                'allow_add' => [
                    'enabled' => true,
                    'new_tag_text' => '',
                    'tag_separators' => '[","]',
                ],
                'transformer' => TagsTransformer::class,
                'placeholder' => 'form.offer.tags.placeholder',
            ])->add('images', FileType::class, [
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                ],
            ])
            ->add('user', HiddenType::class);

        $builder->get('user')->addModelTransformer(
            $this->userTransformer
        );

        $builder->get('images')->addModelTransformer(
            $this->imageTransformer
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
