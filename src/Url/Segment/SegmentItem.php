<?php namespace Dbrouter\Url\Segment;

/**
 * Path item interface
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface SegmentItem 
{
    
    const DOC_TYPE_HTML         = 'html';
    const DOC_TYPE_XML          = 'xml';
    const DOC_TYPE_JSON         = 'json';
    const DOC_TYPE_TEXT         = 'text';
    const DOC_TYPE_JAVASCRIPT   = 'javascript';
    const DOC_TYPE_PNG          = 'png';
    const DOC_TYPE_JPEG         = 'jpeg';
    const DOC_TYPE_GIF          = 'gif';
    
    /**
     * Checks if the current path item has a type.
     * 
     * @return boolean
     */
    public function hasType();
    
    /**
     * Returns the current type.
     * 
     * @return string
     */
    public function getType();
    
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
