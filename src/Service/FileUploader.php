<?php

namespace App\Service;

use RuntimeException;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
   
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = $safeFilename.'_'.uniqid().'.'.$file->guessExtension();

       
       try {
        $file->move(
            $this->getTargetDirectory(), $filename);
       } catch (FileException $e) {
           
        }

        return $filename;
       
       }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;

    }
    }
