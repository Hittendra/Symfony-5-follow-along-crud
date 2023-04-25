<?php

namespace App\Services;

class FileUploader {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function uploadFile(UploadedFile $file)
     {
       
        $filename = md5(uniqid()). '.' . $file->guessClientExtension();

        $file->move(
            $this->getParameter('uploads_dir'),
            $filename
        );

     }
  
    


}