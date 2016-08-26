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

namespace GLICER\Solver\Command;

use GlHtml\GlHtml;

class GlLazyLoadImg
{
    /**
     * @var string rootpath
     */
    private $rootpath;
    
    public function __construct($rootpath) {
        $this->rootpath = $rootpath;
    }

    /**
     * @param $html
     *
     * @throws \Exception
     * @return string
     */
    public function set($html)
    {
        $html = new GlHtml($html);
        $imgs = $html->get('img');
        foreach ($imgs as $img) {
            $src = $img->getAttribute('src');
            if (($src != '#') && ($src != '/nojavascript.php')) {
                $pathimagesrc = $this->rootpath . '/' . $src;
                $imgbin       = @imagecreatefromjpeg($pathimagesrc);
                if (!$imgbin) {
                    $imgbin = @imagecreatefrompng($pathimagesrc);
                    if (!$imgbin) {
                        $imgbin = @imagecreatefromgif($pathimagesrc);
                        if (!$imgbin) {
                            throw new \Exception('Cannot read img : ' . $src);
                        }
                    }
                }
                if ($imgbin) {
                    $width  = imagesx($imgbin);
                    $height = imagesy($imgbin);
                    imagedestroy($imgbin);
                    $img->setAttributes(['width' => $width, 'height' => $height]);
                }

                if (!$img->getAttribute('nolazyload') && !$img->getAttribute('data-original')) {
                    $img->setAttributes(['data-original' => $src, 'src' => '#']);
                }
            }
        }

        return $html->html();
    }
}