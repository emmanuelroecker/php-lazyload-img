<?php

/**
 * Lazy loading images
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   Contact
 * @author    Emmanuel ROECKER <emmanuel.roecker@gmail.com>
 * @author    Rym BOUCHAGOUR <rym.bouchagour@free.fr>
 * @copyright 2012-2013 GLICER
 * @license   Proprietary property of GLICER
 * @link      http://www.glicer.com
 *
 * Created : 8/26/16
 * File : GlLazyLoadImg.php
 *
 */

namespace GlLazyLoadImg;

use GlHtml\GlHtml;

class GlLazyLoadImg
{
    const BLANK = 0;
    const LOSSY = 1;

    /**
     * @var string rootpath
     */
    private $rootpath;

    /**
     * constructor - set root directory to relative url
     *
     * @param string $rootpath
     */
    public function __construct($rootpath)
    {
        $this->rootpath = $rootpath;
    }

    /**
     * create lossy image and encode to data uri format
     * minimal size is jpeg
     *
     * @param     $src          resource GD library
     * @param int $quality      jpeg quality from 0 (poor quality) to 100 (best quality) - default 0
     *
     * @return string
     */
    public function getLossyDataURI($src, $quality = 0)
    {
        ob_start();
        imagejpeg($src, null, $quality);
        $data = ob_get_contents();
        ob_end_clean();

        $base64 = base64_encode($data);

        $mime = 'image/jpeg';

        return ('data:' . $mime . ';base64,' . $base64);
    }

    /**
     * create blank image with same size in data uri format
     * minimal size is gif
     *
     * @param     $src          resource GD library
     * @param int $red          red component background color (default 255)
     * @param int $green        green component background color (default 255)
     * @param int $blue         blue component background color (default 255)
     *
     * @return string
     */
    public function getBlankDataURI($src, $red = 255, $green = 255, $blue = 255)
    {
        $width  = imagesx($src);
        $height = imagesy($src);

        $img   = imagecreatetruecolor($width, $height);
        $bgcol = imagecolorallocate($img, $red, $green, $blue);
        imageFill($img, 0, 0, $bgcol);

        ob_start();
        imagegif($img);
        $data = ob_get_contents();
        ob_end_clean();

        $base64 = base64_encode($data);

        imagedestroy($img);

        $mime = 'image/gif';

        return ('data:' . $mime . ';base64,' . $base64);
    }
   
    /**
     * replace all src attributes from img tags with datauri and set another attribute with old src value
     * support jpeg, png or gif file format
     *
     * @param string $html
     * @param int    $type                  (self::BLANK=0 or self::LOSSY=1)
     * @param string $moveToAttribute       move src value to $attribute (default = data-original)
     * @param array  $excludeAttributesList list of attributes to excludes
     *
     * @throws \Exception
     */
    public function autoDataURI(
        $html,
        $type = self::BLANK,
        $moveToAttribute = 'data-original',
        array $excludeAttributesList = []
    ) {
        $html = new GlHtml($html);
        $imgs = $html->get('img');
        foreach ($imgs as $img) {
            $src          = $img->getAttribute('src');
            $pathimagesrc = $this->rootpath . '/' . $src;

            $imgbin = @imagecreatefromjpeg($pathimagesrc);
            if (!$imgbin) {
                $imgbin = @imagecreatefrompng($pathimagesrc);
                if (!$imgbin) {
                    $imgbin = @imagecreatefromgif($pathimagesrc);
                }
            }

            if ($imgbin) {
                switch ($type) {
                    case self::BLANK:
                        $datauri = $this->getBlankDataURI($imgbin);
                        break;
                    case self::LOSSY:
                        $datauri = $this->getLossyDataURI($imgbin);
                        break;
                    default:
                        throw new \Exception("Type unknown (only self::BLANK=0 or self::LOSSY=1 accepted) : " . $type);
                }

                
                if (!$img->hasAttributes($excludeAttributesList)) {
                    $img->setAttributes([$moveToAttribute => $src, 'src' => $datauri]);
                }
            }
        }
        
        return $html->html();
    }

    /**
     * @param string $html
     * @param array  $excludeAttributesList
     * 
     * @throws \Exception
     * @return string
     */
    public function autoWidthHeight($html,array $excludeAttributesList = [])
    {
        $html = new GlHtml($html);
        $imgs = $html->get('img');
        foreach ($imgs as $img) {
            $src          = $img->getAttribute('src');
            $pathimagesrc = $this->rootpath . '/' . $src;
            $imgbin       = @imagecreatefromjpeg($pathimagesrc);
            if (!$imgbin) {
                $imgbin = @imagecreatefrompng($pathimagesrc);
                if (!$imgbin) {
                    $imgbin = @imagecreatefromgif($pathimagesrc);
                }
            }
            if ($imgbin) {
                $width  = imagesx($imgbin);
                $height = imagesy($imgbin);
                imagedestroy($imgbin);
                $img->setAttributes(['width' => $width, 'height' => $height]);

                if (!$img->hasAttributes($excludeAttributesList)) {
                    $img->setAttributes(['data-original' => $src, 'src' => '#']);
                }
            }
        }

        return $html->html();
    }
}