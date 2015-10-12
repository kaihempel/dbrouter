<?php namespace Dbrouter\Url\Segment;

use Dbrouter\Url\Segment\PlaceholderIdentifier;
use Dbrouter\Database\Mapper\PlaceholderTypeMapper;
use Dbrouter\Exception\Url\PlaceholderException;
use ReflectionClass;

/**
 * Placeholder class
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Placeholder implements PlaceholderType
{
    /**
     * Placeholder identifier
     *
     * @var PlaceholderIdentifier
     */
    protected $id           = null;

    /**
     * Placeholder type mapper instance
     *
     * @var integer
     */
    protected $type_id      = null;

    /**
     * Current type
     *
     * @var string
     */
    protected $type         = '';

    /**
     * Regular expression to filter the value of the placeholder
     *
     * @var string
     */
    protected $regex        = '';

    /**
     * Placeholder type mapper instance
     *
     * @var string
     */
    protected $name         = '';

    /**
     *
     * @var type
     */
    static protected $supportedTypes = array();

    /**
     * Constructor
     *
     * @param string $name
     * @param string $regex
     */
    public function __construct($name, $type, PlaceholderTypeMapper $typemapper)
    {

        if ( ! is_string($name)) {
            throw PlaceholderException::make('Unexpected name given!');
        }

        $this->name = $name;

        $this->setType($name);
        $this->setTypeMapper($typemapper);
        $this->setSupportedTypes();
    }

    /**
     * Sets the supported types array by reflection extraction
     *
     * @return  void
     */
    private function setSupportedTypes()
    {
        // Check if the class variable is already empty

        if (empty(self::$supportedTypes)) {

            // Extract all defined types

            $ref = new ReflectionClass('Placeholder');
            self::$supportedTypes = $ref->getConstants();
        }
    }

    /**
     * Sets the placeholder identifier object
     *
     * @param   PlaceholderIdentifier $id
     * @return  Placeholder
     */
    public function setId(PlaceholderIdentifier $id)
    {
        $this->id = $id;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the placeholder identifier object
     *
     * @return PlaceholderIdentifier
     */
    public function getId()
    {
        return $id;
    }

    /**
     * Checks if the given placeholder type is already defined
     *
     * @param string $type
     * @return boolean
     */
    public function typeExists($type)
    {

    }

    /**
     * Sets the corresponding placeholder type
     *
     * @param   string $type
     * @return  PlaceholderType
     */
    public function setType($type)
    {
        if ( ! is_string($type)) {
            throw PlaceholderException::make('Unexpected type given!');
        }

        if ( ! $this->typeExists($type)) {
            throw PlaceholderException::make('Given type isn\'t defined!');
        }

        $this->type = $type;
    }

    /**
     * Returns the current type.
     *
     * @return string
     */
    public function getType()
    {

    }

    /**
     * Returns the corresponding type ID
     *
     * @return interger
     */
    public function getTypeId()
    {

    }

    /**
     * Returns a defined regex for the given type
     *
     * @param   string $type
     * @return  string
     */
    public function getTypeRegex($type)
    {

    }

    /**
     *
     * @param string $regex
     * @return \Dbrouter\Url\Segment\Placeholder
     * @throws type
     */
    public function setRegex($regex)
    {
        if ( ! is_string($regex)) {
            throw PlaceholderException::make('Unexpected regex given!');
        }

        $this->regex = $regex;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

}