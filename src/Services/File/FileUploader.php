<?php
declare(strict_types=1);

namespace App\Services\File;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $container;

    /**
     * FileUploader constructor.
     *
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function uploadFile(UploadedFile $file)
    {
        $filename = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->container->getParameter('uploads_dir'),
            $filename
        );

        return $filename;
    }
}