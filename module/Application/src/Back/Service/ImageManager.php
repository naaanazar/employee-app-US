<?php

namespace Application\Back\Service;

/**
 * Class ImageManager
 * @package Application\Back\Service
 */
class ImageManager
{

    /**
     * Retrieves the file information (size, MIME type) by image path.
     * @param string $filePath Path to the image file.
     * @return array|bool File information.
     */
    public function getImageFileInfo($filePath)
    {
        // Try to open file
        if (!is_readable($filePath)) {
            return false;
        }

        // Get file size in bytes.
        $fileSize = filesize($filePath);
        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if($mimeType===false)
            $mimeType = 'application/octet-stream';

        return [
            'size' => $fileSize,
            'type' => $mimeType
        ];
    }

    /**
     * Resizes the image, keeping its aspect ratio.
     * @param string $filePath
     * @param int $desiredWidth
     * @return string Resulting file name.
     */
    public  function resizeImage($filePath, $desiredWidth = 240, $filename = null)
    {
        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);
        // Calculate aspect ratio
        $aspectRatio = $originalWidth/$originalHeight;
        // Calculate the resulting height
        $desiredHeight = $desiredWidth/$aspectRatio;
        // Get image info
        $fileInfo = $this->getImageFileInfo($filePath);

        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) =='image/png')
            $originalImage = imagecreatefrompng($filePath);
        else
            $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
            $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);
        // Save the resized image to temporary location

        if (null === $filename) {
            $filename = tempnam("/tmp", "FOO");
        }

        imagejpeg($resultingImage, $filename, 80);

        // Return the path to resulting image.
        return preg_replace('/^' . addcslashes(BASE_PATH, '/') . '/', '', $filename);
    }

}