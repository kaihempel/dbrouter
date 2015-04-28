<?php namespace Dbrouter\Url\Segment;

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
class UrlSegmentAnalyzer implements SegmentExtentsion
{
    /**
     * Item instance
     * 
     * @var UrlSegmentItem 
     */
    protected $item         = NULL;
    
    /**
     * Placeholder depending regex
     * 
     * @var string 
     */
    protected $regex        = NULL;
    
    /**
     * Placeholder name
     * 
     * @var string 
     */
    protected $placeholder  = NULL;
    
    /**
     * File extentsion
     *
     * @var string 
     */
    protected $extentsion   = NULL;
    
    /**
     * Checks the type of the item
     * 
     * @param \Dbrouter\Url\Segment\UrlSegmentItem $item
     * @return void
     */
    public function process(UrlSegmentItem $item) 
    {
     
        // Register the item
        
        $this->item = $item;
        
        // Wildcard element, nothing to do!
        
        if ($this->isWildcard()) {
            return;
        }
        
        // Check if the current segment is a placeholder
        
        $matches = array();
        if (preg_match('/^\{(.*?)\}$/', $this->item->getValue(), $matches)) {
            
            $this->regex        = preg_replace('/^\{(.*?)\}$/', '(.\*?)', $this->item->getValue());
            $this->placeholder  = $matches[1];
        
        // or maybe a document with extentsion
            
        } else if (preg_match('/\.([a-z]+)$/i', $this->item->getValue(), $matches)) {
            
            $this->extentsion   = $matches[1];
        }
        
    }
    
    /**
     * Checks if already a item is set
     * 
     * @return boolean
     */
    public function isSetItem() 
    {
        return (empty($this->item)) ? false : true;
    }
    
    /**
     * Checks if the current item is a placeholder
     * 
     * @return boolean
     */
    public function isPlaceholder() 
    {
        return (empty($this->regex)) ? false : true;
    }
    
    /**
     * Returns the placeholder name
     * 
     * @return string
     */
    public function getPlaceholderName() 
    {
        return $this->placeholder;
    } 
    
    /**
     * Returns the regex for the placeholder
     * 
     * @return string
     */
    public function getRegex() 
    {
        return $this->regex;
    }
    
    /**
     * Checks if the current item is a wildcard
     * 
     * @return boolean
     */
    public function isWildcard() 
    {
        // No item set, it's definitely no wildcard
        
        if ($this->isSetItem() === false) {
            return false;
        }
        
        // Check the value of the current item
        
        return ($this->item->getValue() == '*') ? true : false;
    }
    
    /**
     * Checks if the current item is a wildcard
     * 
     * @return boolean
     */
    public function isFile() 
    {
        return ($this->hasExtentsion()) ? true : false;
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
     * @return string|NULL
     */
    public function getExtentsion()
    {
        return $this->extentsion;
    }
}

