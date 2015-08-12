<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Exception\Url\UrlSegmentItemException;
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
     * @param   string                  $segment
     * @param   UrlIdentifier           $urlId
     * @param   UrlSegmentIdentifier    $id
     * @param Analyzer $analyzer
     * @return  UrlSegmentItem
     * @throws  UrlSegmentItemException
     */
    public static function make($segment, UrlIdentifier $urlId = null, UrlSegmentIdentifier $id = null, Analyzer $analyzer = null)
    {

        if (empty($segment) || ! is_string($segment)) {
            throw UrlSegmentItemException::make('Unexpected segment given!');
        }

        // Create item instance

        $item = new UrlSegmentItem($segment);

        // Set url ID

        if ( ! empty($urlId)) {
            $item->setUrlId($urlId);
        }

        // Set segment ID

        if ( ! empty($id)) {
            $item->setId($id);
        }

        // Attach analyzer to current item

        if ( ! empty($analyzer)) {
            $item->attachAnalyzer($analyzer);
        }

        return $item;
    }

}