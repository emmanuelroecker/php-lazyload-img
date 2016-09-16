<?php
/**
 * Test GlHtml
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   GlLinkChecker\Tests
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://dev.glicer.com/
 *
 * Created : 04/04/15
 * File : GlLinkChecker.php
 *
 */
namespace GlLazyLoadImg\Tests;

use GlLazyLoadImg\GlLazyLoadImg;

/**
 * @covers        \GlLazyLoadImg\GlLazyLoadImg
 * @backupGlobals disabled
 */
class GlLazyLoadImgTest extends \PHPUnit_Framework_TestCase
{   
    public function test1x1GifDataURI()
    {
        $expected = 'data:image/gif;base64,R0lGODdhAQABAIAAAPz+/AAAACwAAAAAAQABAAACAkQBADs=';

        $lazyload = new GlLazyLoadImg(__DIR__);

        $imgbin  = @imagecreatefromjpeg(__DIR__ . '/img/test1.jpg');
        $datauri = $lazyload->get1x1GifDataURI();

        $this->assertEquals($expected, $datauri);
    }

    
    public function testLossyGifDataURI()
    { 
        $lazyload = new GlLazyLoadImg(__DIR__,GlLazyLoadImg::LOSSY);

        $expected = file_get_contents(__DIR__ . '/expected/lossydatauri.data');
        
        $imgbin  = @imagecreatefromjpeg(__DIR__ . '/img/test1.jpg');
        $datauri = $lazyload->getLossyGifDataURI($imgbin);

        $percent = 0;
        similar_text($expected, $datauri, $percent);
        
        $this->assertGreaterThan(95, $percent, $datauri);
    }
    

    public function testAutoDataURIBlank()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

        $lazyload = new GlLazyLoadImg(__DIR__);

        $result = $lazyload->autoDataURI($html);

        $fileresult   = __DIR__ . '/result/autodatauriblank.html';
        $fileexpected = __DIR__ . '/expected/autodatauriblank.html';

        file_put_contents($fileresult, $result);

        $this->assertFileEquals($fileexpected, $fileresult);
    }

    
    public function testAutoDataURILossy()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

        $lazyload = new GlLazyLoadImg(__DIR__,GlLazyLoadImg::LOSSY);

        $result = $lazyload->autoDataURI($html);

        $fileexpected = __DIR__ . '/expected/autodataurilossy.html';

        $expected = file_get_contents($fileexpected);
        
        $percent = 0;
        similar_text($result, $expected, $percent);

        $this->assertGreaterThan(95,$percent);        
    }
    
    
    public function testAutoWidthHeightFilter()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"><img src="img/test1.jpg" nolazyload></div></body></html>';

        $lazyload = new GlLazyLoadImg(__DIR__,GlLazyLoadImg::BLANK, 'data-original', ['nolazyload']);

        $result = $lazyload->autoDataURI($html);

        $fileresult   = __DIR__ . '/result/autodataurifilter.html';
        $fileexpected = __DIR__ . '/expected/autodataurifilter.html';

        file_put_contents($fileresult, $result);

        $this->assertFileEquals($fileexpected, $fileresult);
    }
}