<?php namespace Dbrouter\Url;

use Dbrouter\Exception\Url\UrlIdentifierException;

/**
 * Url identifier class
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlIdentifier 
{
    /**
     * ID value
     * 
     * @var integer 
     */
    private $id = NULL;
           
    /**
     * Constructor
     * 
     * @param integer $id
     * @throws Dbrouter\Exception\Url\UrlIdentifierException
     */
    public function __construct($id) 
    {
        if ( ! is_numeric($id)) {
            throw UrlIdentifierException::make('Unexpected ID value given!');
        }
        
        $this->id = $id;
    } 
    
    /**
     * Returns the identifier
     * 
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }
    
    /**
     * Checks if the current ID is zero
     * 
     * @return boolean
     */
    public function isEmpty() 
    {
        return (empty($this->id)) ? true : false;
    }
    
}
