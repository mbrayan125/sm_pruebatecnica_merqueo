<?php

namespace App\ProjectInterfaces\Helpers;

interface FilesHelperInterface
{

    public static function validateFileAccess(
        string ...$path
    );

    public static function decompressZipFile(
        string $compressedPath,
        string $outputPath
    );

    public static function getFileMimeType(
        string $path
    ): string;

    public static function createRoute(
        string ...$path
    ): string;

    public static function copyFile(
        string $pathFrom,
        string $pathTo
    );

    public static function createDirectory(
        string $path
    );
}