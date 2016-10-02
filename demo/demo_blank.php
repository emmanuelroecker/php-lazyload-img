<?php
require __DIR__ . '/../vendor/autoload.php';

use GlLazyLoadImg\GlLazyLoadImg;

$html = file_get_contents(__DIR__ . '/_demo3.html');
$lazyload = new GlLazyLoadImg(__DIR__,GlLazyLoadImg::BLANK, 'data-original');
$result = $lazyload->autoDataURI($html);
file_put_contents(__DIR__ . '/demo3_blank.html', $result);

