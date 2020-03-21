<?php
/**
 * Photo upload listener.
 */

namespace App\EventListener;

use App\Entity\Image;
use App\Service\FileUploader;
use Exception;

/**
 * Class PhotoUploadListener.
 */
class ImageListener
{
    /**
     * Uploader service.
     *
     * @var FileUploader|null
     */
    protected ?FileUploader $fileUploader = null;

    /**
     * PhotoUploadListener constructor.
     *
     * @param FileUploader $fileUploader File uploader service
     */
    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * Pre persist.
     *
     * @param Image $image
     *
     * @throws Exception
     */
    public function prePersist(Image $image): void
    {
        $this->uploadFile($image);
    }

    /**
     * Pre update.
     *
     * @param Image $image
     *
     * @throws Exception
     */
    public function preUpdate(Image $image): void
    {
        $this->uploadFile($image);
    }

    /**
     * @param Image $image
     *
     * @throws Exception
     */
    private function uploadFile(Image $image): void
    {
        $fileName = $this->fileUploader->upload($image->getUploadedFile());
        $image->setFile($fileName);
    }
}
