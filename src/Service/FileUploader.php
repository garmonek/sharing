<?php
/**
 * @license MIT
 */

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader.
 */
class FileUploader
{
    /**
     * Target directory.
     *
     * @var string
     */
    protected string $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory Target directory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload file.
     *
     * @param UploadedFile $file
     *
     * @throws Exception
     *
     * @return string File name
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = bin2hex(random_bytes(32)).'.'.$file->guessExtension();
        try {
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $exception) {
            dd($exception);
        }

        return $fileName;
    }

    /**
     * Get target directory.
     *
     * @return string Target directory
     */
    public function getTargetDir(): string
    {
        return $this->targetDirectory;
    }
}
