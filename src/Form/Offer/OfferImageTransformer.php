<?php /** @noinspection MessDetectorValidationInspection */

/**
 * @license MIT
 */

namespace App\Form\Offer;

use App\Entity\Image;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;

/**
 * Class OfferUserTransformer
 */
class OfferImageTransformer implements DataTransformerInterface
{
    /**
     * @var Security
     */
    private Security $security;

    /**
     * OfferUserTransformer constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($files)
    {
        return array_map(function (UploadedFile $file) {
            return (new Image())
                ->setName($file->getClientOriginalName())
                ->setMimeType($file->getMimeType())
                ->setUser($this->security->getUser())
                ->setUploadedFile($file);
        }, $files);
    }
}
