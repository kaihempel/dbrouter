<?php namespace Dbrouter\Url\Segment;

/**
 * Segment item interface.
 * Defines the methods for the chained list.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface SegmentItem 
{
    /**
     * Checks if the current item is the first one.
     * 
     * @return boolean
     */
    public function isFirstItem();
    
    /**
     * Checks if the current item is the last one.
     * 
     * @return boolean
     */
    public function isLastItem();
    
    /**
     * Returns the item below the current one.
     * 
     * @return SegmentItem;
     */
    public function getBelow();
    
    /**
     * Returns the item above the current one.
     * 
     * @return SegmentItem;
     */
    public function getAbove();
    
    /**
     * Adds the next path item.
     * 
     * @param \Dbrouter\Url\SegmentItem $item
     * @return \Dbrouter\Url\SegmentItem
     */
    public function attachSegmentItemAbove(SegmentItem $item);
    
    /**
     * Adds the path item before.
     * 
     * @param \Dbrouter\Url\SegmentItem $item
     * @return \Dbrouter\Url\SegmentItem
     */
    public function attachSegmentItemBelow(SegmentItem $item);
    
}
