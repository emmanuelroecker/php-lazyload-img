# php-lazyload-img

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emmanuelroecker/php-lazyload-img/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/emmanuelroecker/php-lazyload-img/?branch=master)
[![Build Status](https://travis-ci.org/emmanuelroecker/php-lazyload-img.svg?branch=master)](https://travis-ci.org/emmanuelroecker/php-lazyload-img)
[![Coverage Status](https://coveralls.io/repos/github/emmanuelroecker/php-lazyload-img/badge.svg?branch=master)](https://coveralls.io/github/emmanuelroecker/php-lazyload-img?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/edf81a87-96e7-4620-b4e0-47d75c02b71d/mini.png)](https://insight.sensiolabs.com/projects/edf81a87-96e7-4620-b4e0-47d75c02b71d)
[![Dependency Status](https://www.versioneye.com/user/projects/57cd80ba968d640033602aa5/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57cd80ba968d640033602aa5)

Lazy loading images with data:uri

## Server side with PHP

### Setting width and height

Set width and height attributes to all <img> tags in html

```php
$html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

$lazyload = new GlLazyLoadImg(__DIR__); //root directory for relative url

$result = $lazyload->autoWidthHeight($html);
```

$result contain :

```html
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <div>
            <img src="#" width="256" height="256" data-original="img/test1.jpg">
        </div>
    </body>
</html>
```

### Using a blank image

Add blank image with same size in data:uri attributes to all <img> tags in html

```php
$html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

$lazyload = new GlLazyLoadImg(__DIR__);

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

### Using a lossy jpeg image

Add jpeg lossy image in data:uri attributes to all <img> tags in html

```php
$html = '<!DOCTYPE html><html><head></head><body><div><img src="img/test1.jpg"></div></body></html>';

$lazyload = new GlLazyLoadImg(__DIR__);

$result = $lazyload->autoDataURI($html, GlLazyLoadImg::LOSSY);
```

$result contain :

```html
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <div>
            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD//gA6Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBxdWFsaXR5ID0gMAr/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAEAAQADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwCSiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigApCQOtLTWGRgetABvX1/Q/4Ub19f0P8AhTNh9v1/wo2H2/X/AAoAfvX1/Q/4Ub19f0P+FREYOKUKSM8UASb19f0P+FG5fX9DTNh9v1/wpQhBB46/57UASU3evr+h/wAKU9D9D/KoBzxQBNvX1/Q/4Ub19f0P+FM2H2/X/CjYfb9f8KAH719f0P8AhShgehqPYfb9f8KcqkHJx0oAfRRUbt2H40AOLAd6TePf/P40wKTTvL9/0oAdvWlBB6Go9h9RQoIYZHr/ACNAEtN3r6/of8KU8gj2NR7D7fr/AIUAP3r6/of8KN6+v6H/AApmw+36/wCFIVI696AJN6+v6H/Cjevr+h/wqMKT07Uuw+36/wCFAD96+v6H/CnVFsPt+v8AhUg4AHsKAFooooAKKKKACiiigAooooAhf7x/D+QqRPuj8f5mo3+8fw/kKkT7o/H+ZoAdRRRQAh6H6H+VQjqPqP51Meh+h/lUI6j6igCeikyPUfmKMj1H5igBaKTI9R+YoyD0OaAFqvVioGGCaAJhwKWow/Y/nT9wPcUALRRRQAUUUUAFRydvx/pUlRydvx/pQAR9/wAP61JUcff8P61JQAUUUUAFFFFABRRRQAUUUUAFFFFAEL/eP4fyFSJ90fj/ADNRv94/h/IVIn3R+P8AM0AOooooAQ9D9D/KoKnPQ/Q/yqEdR9R/OgBdjen6ijY3p+oqaigCHY3p+op6AjOfb+tPooAKQgHrS0UARFD25puCOxqeigCAEjoakV88H86cVB/xqEjBxQBPRSA5ANLQAVHJ2/H+lSVHJ2/H+lABH3/D+tSVHH3/AA/rUlABRRRQAUUUUAFFFFABRRRQAUUUUAQv94/h/IVIn3R+P8zUb/eP4fyFSJ90fj/M0AOooooAQ9D9D/KoR1H1H86mPQ/Q/wAqgHHNAFiiot59v1/xo3n2/X/GgCWiot59v1/xpVYkgcUASUUVACR7UAT0VFvPoKXzPb9f/rUASVAxyTSlifahVz9KAJF+6KdRRQAVHJ2/H+lSVHJ2/H+lABH3/D+tSVHH3/D+tSUAFFFFABRRRQAUUUUAFFFFABRRRQA0qDyR/OlAxwKWigAooooAKbsX0/U/406igBuxfT9T/jRsX0/U/wCNOooAbsX0/U/40BQOQP506igApCAeopaKAG7FpNg9/wDP4U+igBoVR2/OnUUUAFFFFABSEA9aWigBAAOlLRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAf/2Q==" data-original="img/test1.jpg">
        </div>
    </body>
</html>
```

## Client side with javascript

By example, you can use [LazyLoad (aka Vanilla LazyLoad)](https://github.com/verlok/lazyload)