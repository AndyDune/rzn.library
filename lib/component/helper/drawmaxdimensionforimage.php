<?
namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class DrawMaxDimensionForImage extends HelperAbstract
{
    public function __invoke($width, $height, $max = null, $min = null)
    {
        if (is_array($max)) {
            $maxWidth = $max[0];
            $maxHeight = $max[1];
        } else
            $maxHeight = $maxWidth = $max;

        if (is_array($min)) {
            $minWidth = $min[0];
            $minHeight = $min[1];
        } else
            $minHeight = $minWidth = $min;


        if ($width > $height) {
            if ($maxWidth and $width > $maxWidth)
                $width = $maxWidth;
            else if ($minWidth and $width < $minWidth)
                $width = $minWidth;

            $this->_result = 'width="' . $width . '"';
        } else {
            if ($maxHeight and $height > $maxHeight)
                $height = $maxHeight;
            else if ($minHeight and $height < $minHeight)
                $height = $minHeight;

            $this->_result = 'height="' . $height . '"';

        }
        return $this;
    }

    public function get()
    {
        return ' ' . $this->_result . ' ';
    }
}