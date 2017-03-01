<?php

namespace Application\Back\Service;

use Application\Model\File;

/**
 * Class FileManager
 * @package Application\Back\Service
 */
class FileManager
{

    /**
     * @var array
     */
    private $allowedTypes = [
        'image/png',
        'image/pjpeg',
        'image/jpeg',
        'image/gif',
        'image/bmp',
        'text/plain',
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword',
        'application/vnd.oasis.opendocument.text'
    ];

    /**
     * @return array
     */
    public function getAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * @param array $allowedTypes
     */
    public function setAllowedTypes(array $allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
    }

    /**
     * FileManager constructor.
     * @param array|null $allowedTypes
     */
    public function __construct($allowedTypes = null)
    {
        if (null !== $allowedTypes) {
            $this->setAllowedTypes($allowedTypes);
        }
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function remove($filePath)
    {
        $basePath = BASE_PATH . DIRECTORY_SEPARATOR . $filePath;

        if (true === is_dir($filePath)) {

            foreach (scandir($basePath) as $file) {
                $this->remove(BASE_PATH . DIRECTORY_SEPARATOR . $file);
            }

        }

        return @unlink($filePath);

    }

    /**
     * @param $filePath
     * @return int|float
     */
    public function getSize($filePath)
    {
        $basePath = BASE_PATH . DIRECTORY_SEPARATOR . $filePath;

        if (true === file_exists($basePath)) {
            return filesize($basePath) / 1024 / 1024;
        }

        return 0;
    }

    /**
     * @param array $file
     * @return File|bool
     */
    public function storeFile(array $file, $path)
    {
        if (false === isset($file['name'])
            || false === isset($file['type'])
            || false === isset($file['tmp_name'])
            || false === isset($file['size'])
            || (false === empty($this->allowedTypes)
                && false === in_array($file['type'] , $this->getAllowedTypes())
            )
        ) {
            return false;
        }

        $fileModel = new File();
        $fileModel->setPath($path . DIRECTORY_SEPARATOR .  $file['name']);
        $fileModel->setSize($file['size'] / 1024 / 1024);
        $fileModel->setMime($file['type']);
        $fileModel->setName($file['name']);

        $recursivePath = BASE_PATH;

        foreach (explode('/', $path) as $pathItem) {
            $recursivePath .= DIRECTORY_SEPARATOR . $pathItem;
            if (false === file_exists($recursivePath)) {
                mkdir($recursivePath);
            }
        }

        move_uploaded_file($file['tmp_name'], BASE_PATH . DIRECTORY_SEPARATOR . $fileModel->getPath());

        return $fileModel;
    }

    /**
     * @param array $files
     * @return File[]
     */
    public function storeFiles(array $files, $path)
    {
        $result = [];

        foreach ($files as $file) {
            if ($file !== false) {
                $result[] = $this->storeFile((array)$file, $path);
            }
        }

        return $result;
    }

}