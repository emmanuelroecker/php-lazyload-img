# php-lazyload-img

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emmanuelroecker/php-lazyload-img/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/emmanuelroecker/php-lazyload-img/?branch=master)
[![Build Status](https://travis-ci.org/emmanuelroecker/php-lazyload-img.svg?branch=master)](https://travis-ci.org/emmanuelroecker/php-lazyload-img)
[![Coverage Status](https://coveralls.io/repos/github/emmanuelroecker/php-lazyload-img/badge.svg?branch=master)](https://coveralls.io/github/emmanuelroecker/php-lazyload-img?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/edf81a87-96e7-4620-b4e0-47d75c02b71d/mini.png)](https://insight.sensiolabs.com/projects/edf81a87-96e7-4620-b4e0-47d75c02b71d)
[![Dependency Status](https://www.versioneye.com/user/projects/57cd80ba968d640033602aa5/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57cd80ba968d640033602aa5)

Autoset html tags/attributes to lazy loading lossless/lossy images

## Server side with PHP

### Using a blank image and set width/height

* Add 1x1 gif blank image in data:uri attribute to all <img> tags in html
* Set width and height attributes with original image size (Browser is going to rescale automatically with width/height attribute)

```php
$html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

$lazyload = new GlLazyLoadImg(__DIR__); //root directory parameter for relative url

$result = $lazyload->autoDataURI($html);
```

$result contain : 

```html
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <div>
            <img src="data:image/gif;base64,R0lGODdhAAEAAYAAAPz+/AAAACwAAAAAAAEAAQAC/oSPqcvtD6OctNqLs968+w+G4kiW5omm6sq27gvH8kzX9o3n+s73/g8MCofEovGITCqXzKbzCY1Kp9Sq9YrNarfcrvcLDovH5LL5jE6r1+y2+w2Py+f0uv2Oz+v3/L7/DxgoOEhYaHiImKi4yNjo+AgZKTlJWWl5iZmpucnZ6fkJGio6SlpqeoqaqrrK2ur6ChsrO0tba3uLm6u7y9vr+wscLDxMXGx8jJysvMzc7PwMHS09TV1tfY2drb3N3e39DR4uPk5ebn6Onq6+zt7u/g4fLz9PX29/j5+vv8/f7/8PMKDAgQQLGjyIMKHChQwbOnwIMaLEiRQrWryIMaPGYI0cO3r8CDKkyJEkS5o8iTKlypUsW7p8CTOmzJk0a9q8iTOnzp08e/r8CTSo0KFEixo9ijSp0qVMmzp9CjWq1KlUq1q9ijWr1q1cu3r9Cjas2LFky5o9izat2rVs2x4rAAA7" data-original="img/test1.jpg">
        </div>
    </body>
</html>
```

### Using a lossy image and set width/height

* Add reduced gif image in data:uri attribute to all <img> tags in html
* Set width and height attributes with original image size (Browser is going to rescale automatically with width/height attribute)

```php
$html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

$lazyload = new GlLazyLoadImg(__DIR__,GlLazyLoadImg::LOSSY); //root directory parameter for relative url

$result = $lazyload->autoDataURI($html);
```

$result contain :

```html
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <div>
            <img src="data:image/gif;base64,R0lGODdhSwBLAMQAAAQCBIyKjNTS1Dw6POzq7BwaHKyqrPT29BQSFFxeXAwKDLy6vPz+/AQGBJyenNza3ExKTPTy9CwuLPz6/GxqbMTCxAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAASwBLAAAF/iAjjmRpnmiqrmzrvnAsz3Rt33iu73zv/8CgcEgsGo/IpHLJbDqf0Kh0Sq1ar9isdsvter/gsHEycZVr5zL5tWY1APC4XC6a28+jxRveGETUdoFweCuCgXWGACUACoIME4lzBy6TJHEoAA0tcQQMBwcTFHCYozqXJ6UqAqkupzmui4orEqybtTawlrIqBbeFvjS5I8CPIgGZxa3EMsJ1mixyEJ3QyzHNDJGxcxUq1zPX2SUJgYS6uzjg5zAIl+XY1TDpM2UOcAKo8C/yNXAF+Oq4gOVLAQfBvx37Vrh79MbAQVPA3izU9dCEN2YC9xxCFEdjJgWkAPLLtDASnlV2Lp6F3DGRRbk1LU/E1DEThxoxOHPq3Mmzp8+fQIMKHUq0qNGjSJMqXcq0qdMdIQAAOw==" width="256" height="256" data-original="img/test1.jpg">
        </div>
    </body>
</html>
```

## Client side with javascript

By example, you can use [LazyLoad (aka Vanilla LazyLoad)](https://github.com/verlok/lazyload)
