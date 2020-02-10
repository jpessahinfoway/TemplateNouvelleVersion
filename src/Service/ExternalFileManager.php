<?php


namespace App\Service;


use Symfony\Component\Translation\Exception\NotFoundResourceException;
use \Exception;

class ExternalFileManager
{

    public function __construct()
    {

    }

    public function getFile(string $path)
    {
        die("Hello from ExternalFileManager::getFile !");
    }

    public function getFileContent(string $path)
    {

        if($this->fileExist($path))
        {

            if(!is_readable($path))
                throw new Exception(sprintf("Error ! Cause : '%s' file is not readable !", $path));


            return file_get_contents($path);

        }


    }

    public function writeInFile(string $path, $content)
    {

        if($this->fileExist($path))
        {

            if(!is_writable($path))
                throw new Exception(sprintf("Error ! Cause : '%s' file is not writeable !", $path));

            die("Hello from ExternalFileManager::writeInFile !");

        }

    }

    private function fileExist(string $path)
    {

        if(!file_exists($path))
            throw new NotFoundResourceException(sprintf('Error ! Cause : "%s" file is not found !', $path));

        return true;

    }

}