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
     * @var int
     */
    private $type;


    /**
     * @var string
     */
    private $moveToAttribute;

    /**
     * @var array
     */
    private $excludeAttributesList;

    /**
     * constructor - set root directory to relative url
     *
     * @param string $rootpath
     * @param int    $type
     * @param string $moveToAttribute
     * @param array  $excludeAttributesList
     */
    public function __construct(
        $rootpath,
        $type = self::BLANK,
        $moveToAttribute = 'data-original',
        array $excludeAttributesList = []
    ) {
        $this->rootpath              = $rootpath;
        $this->type                  = $type;
        $this->moveToAttribute       = $moveToAttribute;
        $this->excludeAttributesList = $excludeAttributesList;
    }

    
    private function gcd($a,$b) {
         return ($a % $b) ? $this->gcd($b,$a % $b) : $b;
    }
    
    /**
     * create lossy image and encode to data uri format
     * minimal size is jpeg
     *
     * @param     $src          resource GD library
     * @param int $minwidth     min width in pixels (height autocalculte)
     *                          
     * @return string           data uri format
     */
    public function getLossyDataURI($src, $minwidth = 75)
    {            
        $src = imagescale($src,$minwidth,-1,IMG_NEAREST_NEIGHBOUR);
        
        ob_start();
        imagegif($src);
        $data = ob_get_contents();
        ob_end_clean();

        imagedestroy($src);
        
        $base64 = base64_encode($data);

        $mime = 'image/gif';
      
        return ('data:' . $mime . ';base64,' . $base64);
    }

    /**
     * create blank image with same size in data uri format
     * minimal size is gif
     *
     * @param      $src          resource GD library
     * @param int  $red          red component background color (default 255)
     * @param int  $green        green component background color (default 255)
     * @param int  $blue         blue component background color (default 255)
     * @param bool $minsize      rescale to min size (default true)
     *
     * @return string            data uri format
     */
    public function getBlankDataURI($src, $red = 255, $green = 255, $blue = 255, $minsize = true)
    {
        $width  = imagesx($src);
        $height = imagesy($src);

        if ($minsize) {
            $gcd    = $this->gcd($width, $height);
            $width  = $width / $gcd;
            $height = $height / $gcd;
        }
        
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
     * find type of image and open it
     *
     * @param string $file
     *
     * @return bool|resource
     */
    private function openImage($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        $size = getimagesize($file);
        switch ($size["mime"]) {
            case "image/jpeg":
                $im = imagecreatefromjpeg($file);
                break;
            case "image/gif":
                $im = imagecreatefromgif($file);
                break;
            case "image/png":
                $im = imagecreatefrompng($file);
                break;
            default:
                $im = false;
                break;
        }

        return $im;
    }

    /**
     * replace all src attributes from img tags with datauri and set another attribute with old src value
     * support jpeg, png or gif file format
     *
     * @param string $html
     * @param bool   $minsize  rescale to minsize (default true)
     * 
     * @throws \Exception
     * @return string
     */
    public function autoDataURI($html, $minsize=true)
    {
        $html = new GlHtml($html);
        $imgs = $html->get('img');
        foreach ($imgs as $img) {
            $src          = $img->getAttribute('src');
            $pathimagesrc = $this->rootpath . '/' . $src;

            $imgbin = $this->openImage($pathimagesrc);
            if ($imgbin) {
                switch ($this->type) {
                    case self::BLANK:
                        $datauri = $this->getBlankDataURI($imgbin);
                        break;
                    case self::LOSSY:
                        $datauri = $this->getLossyDataURI($imgbin);
                        break;
                    default:
                        throw new \Exception("Type unknown (only self::BLANK=0 or self::LOSSY=1 accepted) : " . $this->type);
                }

                if ($minsize) {
                    // keep original size
                    $width  = imagesx($imgbin);
                    $height = imagesy($imgbin);
                    $img->setAttributes(['width' => $width, 'height' => $height]);
                }

                if (!$img->hasAttributes($this->excludeAttributesList)) {
                    $img->setAttributes([$this->moveToAttribute => $src, 'src' => $datauri]);
                }
                imagedestroy($imgbin);
            }
        }

        return $html->html();
    }

    /**
     * @param string $html
     *
     * @throws \Exception
     * @return string
     */
    public function autoWidthHeight($html)
    {
        $html = new GlHtml($html);
        $imgs = $html->get('img');
        foreach ($imgs as $img) {
            $src          = $img->getAttribute('src');
            $pathimagesrc = $this->rootpath . '/' . $src;

            $imgbin = $this->openImage($pathimagesrc);
            if ($imgbin) {
                $width  = imagesx($imgbin);
                $height = imagesy($imgbin);
                $img->setAttributes(['width' => $width, 'height' => $height]);

                if (!$img->hasAttributes($this->excludeAttributesList)) {
                    $img->setAttributes(['data-original' => $src, 'src' => '#']);
                }
                imagedestroy($imgbin);
            }
        }

        return $html->html();
    }
}
