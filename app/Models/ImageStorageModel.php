<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 5. 1. 2018
 * Time: 11:55
 */

namespace App\Models;

use \Nette\Object,
    \Nette\Utils\Image,
    \Nette\Utils\FileSystem,
    \Nette\Http\FileUpload;

/**
 * Class ImageStorageModel
 */
class ImageStorageModel extends Object
{
//    /** @var $uploadsDir */
//    public $uploadsDir;

    /** @var $imagesDir */
    public $imagesDir;

    public function __construct($dir)
    {
        $this->imagesDir = $dir;
        //$this->uploadsDir = '/uploads/images';
    }

    /**
     * @param FileUpload $file
     * @param $newFileName
     * @param $moduleImagesDir
     * @param $moduleThumbsDir
     *
     * @throws \Nette\Utils\UnknownImageFileException
     * @throws \Nette\NotSupportedException
     * @throws \Nette\IOException
     */
    public function upload($file, $newFileName = '', $moduleImagesDir, $moduleThumbsDir)
    {
        if($file->isImage() && $file->isOk()) {
            //if newFileName is not set, get new filename
            if ($newFileName === '') {
                $newFileName = $this->getNewFileName($file, $moduleImagesDir);
            }
            //move main file if we have unique filename
            $file->move($this->imagesDir . $moduleImagesDir . $newFileName);

            //create thumb
            $image = Image::fromFile($this->imagesDir . $moduleImagesDir . $newFileName);
            if ($image->getWidth() > $image->getHeight()) {
                $image->resize(140, NULL);
            } else {
                $image->resize(NULL, 140);
            }
            $image->sharpen();
            //create thumbs folder if it not exists
            if (!is_dir($this->imagesDir . $moduleImagesDir . $moduleThumbsDir)) {
                FileSystem::createDir($this->imagesDir . $moduleImagesDir . $moduleThumbsDir);
            }
            //save thumb
            $image->save($this->imagesDir . $moduleImagesDir . $moduleThumbsDir . $newFileName);
        }
    }

    /**
     * @param FileUpload $file
     * @param $moduleImagesDir
     *
     * @return string
     */
    public function getNewFileName($file, $moduleImagesDir): string
    {
        //get file extension
        $file_ext = strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), '.', -1)));
        $file_name = '';
        while (true) {
            //random filename, can use \Nette\Strings::random()
            $file_name = uniqid(random_int(0,20), TRUE) . $file_ext;
            if (!file_exists($this->imagesDir . $moduleImagesDir . $file_name)) {
                break;
            }
        }

        return $file_name;
    }
}