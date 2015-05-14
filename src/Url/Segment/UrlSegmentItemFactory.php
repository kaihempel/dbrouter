<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Exception\Url\UrlSegmentItemException;
use Dbrouter\Url\Segment\UrlSegmentAnalyzer;
use Dbrouter\Url\UrlIdentifier;

/**
 * Path segment item factory
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlSegmentItemFactory
{

    /**
     * Initialize one item object
     * 
     * @param string $segment
     * @param \Dbrouter\Url\UrlIdentifier $id
     * @return \Dbrouter\Url\Segment\UrlSegmentItem
     * @throws \Dbrouter\Url\Segment\UrlSegmentItemException
     */
    public static function make($segment, $id = NULL)
    {

        if (empty($segment) || ! is_string($segment)) {
            throw UrlSegmentItemException::make('Unexpected segment given!');
        }

        // Create item instance

        if ( ! empty($id) && $id instanceof \Dbrouter\Url\UrlIdentifier) {
            $item = self::makeItemWithId($segment, $id);
        } else {
            $item = self::makeItem($segment);
        }

        // Attach analyzer to current item

        $item->attachAnalyzer(new UrlSegmentAnalyzer());

        return $item;
    }

    /**
     * Initialize url segment item object
     * 
     * @param string $segment
     * @return \Dbrouter\Url\Segment\UrlSegmentItem
     */
    private static function makeItem($segment)
    {
        return new UrlSegmentItem($segment);
    }

    /**
     * Initialize url segment item object wíth url identifier object 
     * 
     * @param string $segment
     * @param \Dbrouter\Url\UrlIdentifier $id
     * @return \Dbrouter\Url\Segment\UrlSegmentItem
     */
    private static function makeItemWithId($segment, UrlIdentifier $id)
    {
        return new UrlSegmentItem($segment, $id);
    }

}
