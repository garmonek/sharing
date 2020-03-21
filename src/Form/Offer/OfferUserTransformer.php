<?php /** @noinspection MessDetectorValidationInspection */

/**
 * @license MIT
 */

namespace App\Form\Offer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OfferUserTransformer
 */
class OfferUserTransformer implements DataTransformerInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * OfferUserTransformer constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @param mixed $value
     *
     * @return string
     */
    public function transform($value)
    {
        return '';
    }

    /**
     * @param mixed $value
     *
     * @return UserInterface|null
     */
    public function reverseTransform($value)
    {
        return $this->security->getUser();
    }
}
