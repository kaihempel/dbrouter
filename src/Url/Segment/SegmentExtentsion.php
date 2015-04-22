<?php namespace Dbrouter\Url\Segment;

/**
 * Segment extentsion interface
 * Defines the supported file extentsion and neccessary methods.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface SegmentExtentsion
{
    
    const DOC_TYPE_HTML         = 'html';
    const DOC_TYPE_XML          = 'xml';
    const DOC_TYPE_JSON         = 'json';
    const DOC_TYPE_TEXT         = 'txt';
    const DOC_TYPE_JAVASCRIPT   = 'js';
    const DOC_TYPE_PNG          = 'png';
    const DOC_TYPE_JPEG         = 'jpeg';
    const DOC_TYPE_GIF          = 'gif';
    
    /**
     * Checks if the current path item has a type.
     * 
     * @return boolean
     */
    public function hasExtentsion();
    
    /**
     * Returns the current type.
     * 
     * @return string
     */
    public function getExtentsion();
}