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
    const TYPE_PATH                     = 'path';
    const TYPE_PLACEHOLDER              = 'placeholder';
    const TYPE_WILDCARD                 = 'wildcard';
    const TYPE_FILE                     = 'file';
    
    const SEGMENT_WEIGHT_PATH           = 2;
    const SEGMENT_WEIGHT_PLACEHOLDER    = 1;
    const SEGMENT_WEIGHT_WILDCARD       = 0;
    const SEGMENT_WEIGHT_FILE           = 3;
    
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
     * Returns the current item type
     * 
     * @return string
     */
    public function getType();
    
    /**
     * Returns the current weight
     * 
     * @return integer
     */
    public function getWeight();
    
}
