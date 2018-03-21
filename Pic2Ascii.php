<?php

namespace Pic2Ascii;

class Pic2Ascii
{
    /* origin gd resource */
    protected $gd = null;
    /* smaller origin gd resource */
    protected $zip_gd = null;
    /* ratio for zooming out pic, null means auto */
    protected $ratio = null;
    /* converted string */
    protected $asciiStr = '';
    /* string ascii array */
    protected $ascii=['M', 'N', 'H', 'Q', '$', 'O', 'C', '?', '7', '>', '!', ':', '–', ';', '.'];
    /* string ascii array */
    // protected $ascii = '$@B%8&WM#*oahkbdpqwmZO0QLCJUYXzcvunxrjft/\|()1{}[]?-_+~<>i!lI;:,"^`\'\'.';

    /**
     * Creates a new instance
     *
     * @param GD Resource $gdResource
     */
    public function __construct($gdResource = null)
    {
        if (is_resource($gdResource) &&
            get_resource_type($gdResource) == 'gd'
        )
            $this->gd = $gdResource;
    }

    /**
     *  Convert a pixel to char
     *
     * @param  int $x $y
     * @return string
     */
    protected function convertToAscii($x, $y)
    {
        // get rgb
        $rgb = imagecolorat($this->zip_gd, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        // convert gray pic
        $avg = ($r + $g + $b) / 3;

        /*
         * convert origin pic to gray pic
         * uncomment following code and use storeImage function to get it
         */
        // $grayRGB = ($avg << 16) + ($avg << 8) + $avg;
        // imagesetpixel($this->gd, $x, $y, $grayRGB);
        // return '';

        $weight = round($avg / 18);
        // uncomment when using the longer ascii array
        // $weight = round($avg / 70);
        return $this->ascii[$weight];
    }

    /**
     *  Convert origin pic to ascii string
     *
     * @return Pic2Ascii instance
     */
    public function convert()
    {
        if (!$this->gd)
            return false;

        $sx = imagesx($this->gd);   // width pix
        $sy = imagesy($this->gd);   // height pix
        // zoom out ratio
        $r = $this->getRatio();
        $nsx = round($sx / $r);
        $nsy = round($sy / $r);
        // make pic smaller
        $this->zip_gd = imagecreatetruecolor($nsx, $nsy);
        imagecopyresampled(
            $this->zip_gd,
            $this->gd,
            0,
            0,
            0,
            0,
            $nsx,
            $nsy,
            $sx,
            $sy
        );
        // init string
        $this->asciiStr = '';

        for ($y=0; $y < $nsy; $y++) { 
            for ($x=0; $x < $nsx; $x++) { 
                $this->asciiStr .= $this->convertToAscii($x, $y);
            }
            $this->asciiStr .= "\n";
        }
        return $this;
    }

    /**
     *  Set ratio for zooming out pic
     *
     * @return Pic2Ascii instance
     */
    public function setRatio($r)
    {
        if (is_numeric($r) && $r > 0)
            $this->ratio = $r;

        return $this;
    }

    /**
     *  Set ratio auto for zooming out pic
     *
     * @return Pic2Ascii instance
     */
    public function setRatioAuto()
    {
        $this->ratio = null;
        return $this;
    }

    /**
     *  Get ratio for zooming out pic
     *
     * @return Pic2Ascii instance
     */
    public function getRatio()
    {
        if (!$this->gd)
            return null;

        if ($this->ratio)
            return $this->ratio;

        // width recommended which can just fill the screen
        $y = imagesy($this->gd);   // height pix
        if ($y > 64)
            return $y / 64;
        else 
            return 1;
    }

    /**
     *  Store image from origin gd resource
     *
     * @return boolean
     */
    public function storeImage($path)
    {
        if (!$this->gd)
            return false;

        return imagejpeg($this->gd, $path);
    }

    /**
     *  Write ascii string to $path
     *
     * @return boolean
     */
    public function storeAscii($path)
    {

        $f = fopen($path, 'w');
        if ($f) {
            fwrite($f, $this->asciiStr);
            fclose($f);
            return true;
        } else
            return false;
    }

    /**
     *  Write ascii string to $path
     *
     * @param GD Resource $gdResource
     * @return Pic2Ascii instance
     */
    public function setImage($gdResource)
    {
        if (is_resource($gdResource) &&
            get_resource_type($gdResource) == 'gd'
        )
            $this->gd = $gdResource;

        return $this;
    }

    /**
     *  Destory gd resource
     *
     * @return boolean
     */
    public function destroy()
    {
        if ($this->gd) {
            $result = imagedestroy($this->gd);
            $this->gd = null;
            $this->asciiStr = '';
            if ($this->zip_gd) {
                imagedestroy($this->zip_gd);
                $this->zip_gd = null;
            }
            return $result;
        } else
            return false;
    }
}
