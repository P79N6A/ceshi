<?php namespace Validator;

use Exception\HttpException;
use Sirius\Validation\Rule\AbstractRule;


class Image extends AbstractRule
{

    // first define the error messages
    const MESSAGE = '';
    const LABELED_MESSAGE = '';

    // create constants for the options names help your IDE help you


    // specify default options if you want
    protected $options = array(
        'type' => 1
    );

    // if you want to let the user pass the options as a CSV (eg: 'this,that')
    // you need to provide a `optionsIndexMap` property which will convert the options list
    // into an associative array of options
    protected $optionsIndexMap = array(
        0 => 'type'
    );

    protected $imageTypesMap = array(
        IMAGETYPE_GIF      => 'gif',
        IMAGETYPE_JPEG     => 'jpg',
        IMAGETYPE_JPEG2000 => 'jpg',
        IMAGETYPE_PNG      => 'png',
        IMAGETYPE_PSD      => 'psd',
        IMAGETYPE_BMP      => 'bmp',
        IMAGETYPE_ICO      => 'ico',
    );


    function validate($value, $valueIdentifier = null)
    {
        if (!is_array($value) || empty($value)) {
            return false;
        }

        if($this->options['type'] == 2){
            $config = \Config::get('creative');
        } else {
            $config = \Config::get('app');
        }

        foreach ($value as $image) {
            //download images
            $stream = stream_context_create(
                array(
                    'http' => array(
                        'timeout' => 30
                    )
                )
            );
            $content = @file_get_contents($image, false, $stream);
            if (empty($content)) {
                return false;
            }
            $tmp = tempnam(ROOT_PATH . "/storage/images/", "app_file_");
            file_put_contents($tmp, $content);
            $image_info = getimagesize($tmp);
            $extension = isset($this->imageTypesMap[$image_info[2]]) ? $this->imageTypesMap[$image_info[2]] : false;
            $height = isset($image_info[1]) ? $image_info[1] : 0;
            $width = isset($image_info[0]) ? $image_info[0] : 0;
            @unlink($tmp);

            if (!in_array($extension, ['jpg', 'png'])) {
                throw new HttpException(422, '图片格式错误');
            }

//            if (strlen($content) / 1024 > $config['image_max_size'] || $height != $config['image_height'] || $width != $config['image_width']) {
//                return false;
//            }

        }
        return true;
    }

}