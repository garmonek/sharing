<?php
/**
 * @license MIT
 */

namespace App\DataFixtures\Image;

use App\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageResource
 * @package App\DataFixtures\Image
 */
class ImageResource
{
    /**
     * @var string
     */
    private $dir;

    public function __construct()
    {
        $this->dir = __DIR__;
    }

    /**
     * @param string $name
     *
     * @return Collection
     */
    public function getImages(string $name): Collection
    {
        $finder = Finder::create()
            ->files()->in($this->dir)->name($name.'.*')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true);


        $collection = new ArrayCollection();
        /** @var File $file */
        foreach ($finder as $file) {
            $path = '/tmp/'.$name.'.'.$file->getExtension();
            copy($file, $path);

            $image = new Image();
            $image->setName($name);
            $image->setAlt($name);
            $image->setUploadedFile(
                (new UploadedFile($path, $name, $mimeType = null, $error = null, $test = true))
            );

            $collection->add($image);
        }

        return $collection;
    }
}
