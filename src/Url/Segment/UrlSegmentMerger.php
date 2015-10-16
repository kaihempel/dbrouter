<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Url\Url;
use Dbrouter\Url\Segment\UrlSegmentItem;
use Dbrouter\Exception\Url\UrlException;

/**
 * Url container class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlSegmentMerger
{
    /**
     * Resulting url string
     *
     * @var string
     */
    protected $url = '';

    /**
     * Final segment count after merging
     *
     * @var integer
     */
    protected $segmentCount = 0;

    /**
     * Returns the url string
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Merge method
     *
     * @param UrlSegmentItem $item
     */
    public function merge(UrlSegmentItem $item) {

        if ( ! $item->isFirstItem()) {
            $item = Url::setChainOnFirstItem($item);
        }

        $this->mergeItemValues($item);
    }

    /**
     * Creates the real url string
     *
     * @param UrlSegmentItem $item
     */
    private function mergeItemValues(UrlSegmentItem $item)
    {
        $count = 0;

        // Adds all item values to the url string.

        do {

            // Emergency exits check.

            if ($count > Url::MAX_SEGMENTS) {
                throw UrlException::make('Merge process reached max segment count!');
            }

            // Restet item on the next in the chain.

            if ($count > 0) {
                $item = $item->getAbove();
            }

            $this->url .= '/' . $item->getValue();

            // Increase count.

            $count++;

        // Check if the last item is reached.

        } while ( ! $item->isLastItem());

        // Store the resulting segment count.

        $this->segmentCount = $count;
    }
}