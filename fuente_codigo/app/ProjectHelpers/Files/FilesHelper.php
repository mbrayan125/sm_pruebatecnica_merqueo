<?php

namespace App\ProjectHelpers\Files;

use App\ProjectInterfaces\Helpers\FilesHelperInterface;
use Exception;
use stdClass;
use ZipArchive;

abstract class FilesHelper implements FilesHelperInterface
{

    protected static $separatorCharacter = "/";

    public static function validateFileAccess(string ...$path)
    {
        foreach($path as $singularPath) {
            self::validateSingleFileAccess($singularPath);
        }
    }

    public static function validateSingleFileAccess(string $path)
    {
        if (! file_exists($path)) {
            throw new Exception("The file $path doesn't exists");
        }

        if (! is_file($path)) {
            throw new Exception("The $path is not a regular file");
        }
    }


    public static function decompressZipFile(
        string $compressedPath,
        string $outputPath,
        ?string $element = null
    ) {
        self::validateSingleFileAccess($compressedPath);

        $zip = new ZipArchive;
        $zipDescriptor = $zip->open($compressedPath);
        if ($zipDescriptor !== TRUE) {
            throw new Exception("An error ocurred tryng to open file $compressedPath");
        }
        if (! $zip->extractTo($outputPath, $element) ) {
            throw new Exception("Extract elements failure");
        }
        $zip->close();
    }

    public static function getFileMimeType(string $path): string
    {
        self::validateSingleFileAccess($path);
        return mime_content_type($path);
    }

    public static function copyFile(string $pathFrom, string $pathTo)
    {
        $targetPathInfo = self::getPathInfo($pathTo);
        self::createDirectory($targetPathInfo->containerFolder);

        if (! copy($pathFrom, $pathTo)) {
            throw new Exception(sprintf(
                "Cannot copy file from %s to %s",
                $pathFrom,
                $pathTo
            ));
        }
    }

    public static function createDirectory(string $path)
    {
        if (file_exists($path) && is_dir($path)) {
            return;
        }

        if (! mkdir($path, 0777, true)) {
            throw new Exception("Cannot create directory $path");
        }
    }


    public static function getPathInfo($path)
    {
        $response = new stdClass();
        $response->containerFolder = "/";
        $response->lastElement = null;
        $partsOfPath = explode(self::$separatorCharacter, $path);
        if (sizeof($partsOfPath) > 1) {
            $containerFolder = implode(
                self::$separatorCharacter,
                array_slice($partsOfPath, 0, -1)
            );
            $response->containerFolder = $containerFolder;
            $response->lastElement = $partsOfPath[sizeof($partsOfPath) - 1];
        }
        return $response;
    }
}