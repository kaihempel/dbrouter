<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Exception\Url\UrlSegmentItemException;
use Dbrouter\Url\UrlIdentifier;

/**
 * Path item class
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlSegmentItem implements SegmentItem
{
    /**
     * Current unique url ID
     * 
     * @var type 
     */
    private $url_id     = NULL;
    
    /**
     * Path item value
     * 
     * @var string 
     */
    private $value      = NULL;
    
    /**
     * Path object below
     * 
     * @var type 
     */
    private $below      = NULL;
    
    /**
     * Path object above
     * 
     * @var type 
     */
    private $above      = NULL;
    
    /**
     *
     * @var type 
     */
    private $type       = NULL;
    
    /**
     * 
     * @param \Dbrouter\Url\UrlIdentifier $id
     * @param type $value
     * @throws type
     */
    public function __construct(UrlIdentifier $id, $value) 
    {
        // Set url ID
        
        $this->url_id   = $id;

        // Check and set value
        
        if (empty($value)) {
            throw UrlSegmentItemException::make('No path value given!');
        }
        
        $this->value    = $value;
        
    }
    
    /**
     * Returns the url identifier object
     * 
     * @return \Dbrouter\Url\UrlIdentifier
     */
    public function getUrlId() 
    {
        return $this->url_id;
    }
    
    /**
     * Returns the url path value
     * 
     * @return string
     */
    public function getValue() 
    {
        return $this->value;
    }
    
    /**
     * Checks if the current path item has a type.
     * 
     * @return boolean
     */
    public function hasType()
    {
        return (empty($this->type)) ? false : true;
    }
    
    /**
     * Returns the current type.
     * 
     * @return string|NULL
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Checks if the current item is the first one.
     * 
     * @return boolean
     */
    public function isFirstItem()
    {
        return (empty($this->below)) ? true : false;
    }
    
    /**
     * Checks if the current item is the last one.
     * 
     * @return boolean
     */
    public function isLastItem()
    {
        return (empty($this->above)) ? true : false;
    }
    
    /**
     * Returns the item below the current one.
     * 
     * @return SegmentItem|NULL;
     */
    public function getBelow() 
    {
        return $this->below;
    }
    
    /**
     * Returns the item above the current one.
     * 
     * @return SegmentItem|NULL;
     */
    public function getAbove()
    {
        return $this->above;
    }
    
    /**
     * Adds the next path item.
     * 
     * @param \Dbrouter\Url\SegmentItem $item
     * @return \Dbrouter\Url\SegmentItem
     */
    public function attachSegmentItemAbove(SegmentItem $item) 
    {
        $this->above = $item;
        
        // Register self as item belove inside above.
            // This completes the item Chain.
        
        $this->above->attachSegmentItemBelow($this);
        
        return $this; 
    }
    
    /**
     * Adds the path item before.
     * 
     * @param \Dbrouter\Url\SegmentItem $item
     * @return \Dbrouter\Url\SegmentItem
     */
    public function attachSegmentItemBelow(SegmentItem $item) 
    { 
        $this->below = $item;
        
        return $this; 
    }
}

