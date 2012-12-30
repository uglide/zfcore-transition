<?php
/**
 * JPG image adapter
 * Based on ZFEngine_Image
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 */
class Core_Image_Adapter_Jpg extends Core_Image_Adapter_Abstract
{

    public function  __construct($filename)
    {
        $this->_filename = $filename;
        $this->_image = imagecreatefromjpeg($this->_filename);

        return $this;
    }


    public function save($quality = 100)
    {
        $result = imagejpeg($this->_image, $this->_filename, $quality);

        if ($result === true) {
            return $this;
        }

        throw new Core_Exception(
            sprintf(
                "File '%s' could not be saved. An error occured while processing the file.",
                $this->_filename
            )
        );
    }
}