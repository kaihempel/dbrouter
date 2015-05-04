<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Exception\Url\UrlSegmentItemException;
use Dbrouter\Url\UrlIdentifier;

/**
 * Path segment class
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlSegmentItem implements SegmentItem, SegmentItemAttachAble, SegmentExtentsion
{
    /**
     * Current unique url ID
     * 
     * @var UrlIdentifier
     */
    private $urlId      = NULL;
    
    /**
     * Path item value
     * 
     * @var string 
     */
    private $value      = NULL;
    
    /**
     * Path object below
     * 
     * @var UrlSegmentItem
     */
    private $below      = NULL;
    
    /**
     * Path object above
     * 
     * @var UrlSegmentItem
     */
    private $above      = NULL;
    
    /**
     * Segment analyzer object
     *
     * @var UrlSegmentAnalyzer 
     */
    private $analyzer   = NULL;
    
    /**
     * File extentsion
     *
     * @var string 
     */
    private $extentsion = NULL;
    
    /**
     * Constructor
     * 
     * @param   string              $value          Segment value
     * @param   UrlIdentifier       $id             Url identifier
     * @throws  UrlSegmentItemException
     */
    public function __construct($value, UrlIdentifier $id = NULL) 
    {
        // Check and set value
        
        if (empty($value)) {
            throw UrlSegmentItemException::make('No path value given!');
        }
        
        $this->value = $value;
        
        // Set url ID
        
        if ( ! empty($id)) {
            $this->setId($id);
        }

    }
    
    /**
     * Sets the url identifier object
     * 
     * @param UrlIdentifier $id
     */
    public function setId(UrlIdentifier $id) 
    {
        $this->urlId = $id;
        
        return $this;
    }
    
    /**
     * Returns the url identifier object
     * 
     * @return UrlIdentifier
     */
    public function getUrlId() 
    {
        return $this->urlId;
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
     * Set the segment analyzer
     * 
     * @param   UrlSegmentAnalyzer $analyzer
     * @return  UrlSegmentItem
     * @throws  UrlSegmentItemException
     */
    public function attachAnalyzer(UrlSegmentAnalyzer $analyzer) 
    {   
        $this->analyzer = $analyzer;
        $this->analyzer->process($this);
        
        return $this;
    }
    
    /**
     * Returns the string
     * 
     * @return string
     */
    public function getType() 
    {
        if (empty($this->analyzer)) {
            return NULL;
        }
        
        // Check the analyzer
        
        if ($this->analyzer->isPlaceholder()) {
            return self::TYPE_PLACEHOLDER;
            
        } else if ($this->analyzer->isWildcard()) {
            return self::TYPE_WILDCARD;
            
        } else if ($this->analyzer->isFile()) {
            return self::TYPE_FILE;
        }
        
        return self::TYPE_PATH;
    }
    
    /**
     * Checks if the current path item has a type.
     * 
     * @return boolean
     */
    public function hasExtentsion()
    {
        return (empty($this->extentsion)) ? false : true;
    }
    
    /**
     * Returns the current extentsion type.
     * 
     * @return string|null
     */
    public function getExtentsion()
    {
        return $this->extentsion;
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
     * @return SegmentItem|null;
     */
    public function getBelow() 
    {
        return $this->below;
    }
    
    /**
     * Returns the item above the current one.
     * 
     * @return SegmentItem|null;
     */
    public function getAbove()
    {
        return $this->above;
    }
    
    /**
     * Adds the next path item.
     * 
     * @param   UrlSegmentItem $item
     * @return  UrlSegmentItem
     */
    public function attachSegmentItemAbove(SegmentItemAttachAble $item) 
    {
        $this->above = $item;
        
        // Register self as item belove inside above.
        // This completes the item Chain.
        
        if ($this->above->getBelow() === NULL) {
            $this->above->attachSegmentItemBelow($this);
        }
        
        return $this; 
    }
    
    /**
     * Adds the path item before.
     * 
     * @param   UrlSegmentItem $item
     * @return  UrlSegmentItem
     */
    public function attachSegmentItemBelow(SegmentItemAttachAble $item) 
    { 
        $this->below = $item;
        
        // Register self as item above inside below.
        // This completes the item Chain.
        
        if ($this->below->getAbove() === NULL) {
            $this->below->attachSegmentItemAbove($this);
        }
        
        return $this; 
    }
    
}

