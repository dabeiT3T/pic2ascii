<?php

namespace Pic2Ascii;

require_once ('Pic2Ascii.php');

// convert 
$path = '/path/to/pic';
$res = imagecreatefromjpeg($path);
$p2a = new Pic2Ascii($res);
$p2a->convert()->storeAscii('/path/to/convert/picascii');


// create instance without a parameter.
$path = '/path/to/pic';
$res = imagecreatefromjpeg($path);
$p2a = new Pic2Ascii();
$p2a->setImage($res)->convert()->storeAscii('/path/to/convert/picascii');

// some other uses
$p2a = new Pic2Ascii();
// set a new gd resource
$res = imagecreatefromjpeg('/path/to/picture');
$p2a->setImage($res);
// get the zoom out ratio
$p2a->getRatio();       // return => null  (when gdResource is null)
                        //    |-- => ratio (when user sets the ratio)
                        //    |-- => auto-ratio 
                        // (when user hasn't set the ratio and gdRsource is not null)
// set the ratio
$p2a->setRatio(4);
// set the ratio on auto
$p2a->setRatioAuto();
// store image from origin gd resource
$p2a->storeImage('/path/to/save/picture');
