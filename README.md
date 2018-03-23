# pic2ascii
Convert normal picture to ascii based on php.

## Requirements

- PHP >= 5.4

## Supported Image Libraries

- GD Library (>=2.0)


## Getting Started

- [Basic Usage](https://github.com/dabeiT3T/pic2ascii/blob/master/sample.php)

## Code Examples

```php
// Create an instance 
$path = '/path/to/pic';
$res = imagecreatefromjpeg($path);
$p2a = new Pic2Ascii($res);

// Convert and save the ascii
$p2a->convert()->storeAscii('/path/to/convert/picascii');

// Set or change gd resource
$p2a->setImage($res);

// set zoom-out ratio
$p2a->setRatio(4);

// store image from origin gd resource
$p2a->storeImage('/path/to/save/picture');
```

Refer to the `sample.php` to learn more about Pic2Ascii.

## License

Pic2Ascii is licensed under the [GPL License](http://www.gnu.org/licenses/).