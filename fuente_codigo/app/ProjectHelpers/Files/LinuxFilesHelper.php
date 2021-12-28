<?php

namespace App\ProjectHelpers\Files;

class LinuxFilesHelper extends FilesHelper
{

    public static function createRoute(string ...$path): string
    {
        $slicesGeneratedRoute = array();
        $generatedRoute = null;

        foreach ($path as $pathSlice) {

            /**
             * Se recorre la ruta dividida
             */
            $pathsSliceDivided = explode(self::$separatorCharacter, $pathSlice);
            foreach ($pathsSliceDivided as $pathSliceDivided){
                $generatedSlide = trim($pathSliceDivided);
                if (!empty($generatedSlide)) {
                    $slicesGeneratedRoute[] = $generatedSlide;
                }
            }
        }

        $generatedRoute = implode(self::$separatorCharacter, $slicesGeneratedRoute);
        $generatedRoute = self::$separatorCharacter . $generatedRoute;
        return $generatedRoute;
    }
}