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
use Symfony\Component\Finder\Finder;

/**
 * @covers        \GlLazyLoadImg\GlLazyLoadImg
 * @backupGlobals disabled
 */
class GlLazyLoadImgTest extends \PHPUnit_Framework_TestCase
{   
    public function testBlankDataURI()
    {
        $expected = 'data:image/gif;base64,R0lGODdhAAEAAYAAAPz+/AAAACwAAAAAAAEAAQAC/oSPqcvtD6OctNqLs968+w+G4kiW5omm6sq27gvH8kzX9o3n+s73/g8MCofEovGITCqXzKbzCY1Kp9Sq9YrNarfcrvcLDovH5LL5jE6r1+y2+w2Py+f0uv2Oz+v3/L7/DxgoOEhYaHiImKi4yNjo+AgZKTlJWWl5iZmpucnZ6fkJGio6SlpqeoqaqrrK2ur6ChsrO0tba3uLm6u7y9vr+wscLDxMXGx8jJysvMzc7PwMHS09TV1tfY2drb3N3e39DR4uPk5ebn6Onq6+zt7u/g4fLz9PX29/j5+vv8/f7/8PMKDAgQQLGjyIMKHChQwbOnwIMaLEiRQrWryIMaPGYI0cO3r8CDKkyJEkS5o8iTKlypUsW7p8CTOmzJk0a9q8iTOnzp08e/r8CTSo0KFEixo9ijSp0qVMmzp9CjWq1KlUq1q9ijWr1q1cu3r9Cjas2LFky5o9izat2rVs2x4rAAA7';

        $lazyload = new GlLazyLoadImg(__DIR__);

        $imgbin  = @imagecreatefromjpeg(__DIR__ . '/img/test1.jpg');
        $datauri = $lazyload->getBlankDataURI($imgbin);

        $this->assertEquals($expected, $datauri);
    }

    /*
    public function testLossyDataURI()
    { 
        $lazyload = new GlLazyLoadImg(__DIR__);

        $expected = file_get_contents(__DIR__ . '/expected/lossydatauri.data');
        
        $imgbin  = @imagecreatefromjpeg(__DIR__ . '/img/test1.jpg');
        $datauri = $lazyload->getLossyDataURI($imgbin,100);

        $this->assertEquals($expected, $datauri);
    }
    */

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

    /*
    public function testAutoDataURILossy()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

        $lazyload = new GlLazyLoadImg(__DIR__);

        $result = $lazyload->autoDataURI($html, GlLazyLoadImg::LOSSY);

        $fileresult   = __DIR__ . '/result/autodataurilossy.html';
        $fileexpected = __DIR__ . '/expected/autodataurilossy.html';

        file_put_contents($fileresult, $result);


        $this->assertFileEquals($fileexpected, $fileresult);
    }
    */

    public function testAutoWidthHeight()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

        $lazyload = new GlLazyLoadImg(__DIR__);

        $result = $lazyload->autoWidthHeight($html);

        $fileresult   = __DIR__ . '/result/autowidthheight.html';
        $fileexpected = __DIR__ . '/expected/autowidthheight.html';

        file_put_contents($fileresult, $result);

        $this->assertFileEquals($fileexpected, $fileresult);
    }
}